<?php
/**
 * Analyse backend
 *
 * @package CacheCheck\Cache\Backend
 * @author  Julian Seitz
 */

namespace HDNET\CacheCheck\Cache\Backend;

use TYPO3\CMS\Core\Cache\Backend\AbstractBackend;
use TYPO3\CMS\Core\Cache\Backend\BackendInterface;
use TYPO3\CMS\Core\Cache\Backend\FreezableBackendInterface;
use TYPO3\CMS\Core\Cache\Backend\PhpCapableBackendInterface;
use TYPO3\CMS\Core\Cache\Backend\TaggableBackendInterface;
use TYPO3\CMS\Core\Cache\Exception\InvalidBackendException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class CacheAnalyzerBackend
 */
class CacheAnalyzerBackend extends AbstractBackend implements FreezableBackendInterface, PhpCapableBackendInterface, TaggableBackendInterface {

	/**
	 * Original Backend
	 *
	 * @var AbstractBackend
	 */
	protected $originalBackend;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Build up the object
	 *
	 * @param string $context
	 * @param array  $options
	 */
	public function __construct($context, array $options = array()) {
		$this->options = $options;
		parent::__construct($context, $options);
	}

	/**
	 * Set the cache and init the original backend
	 *
	 * @param FrontendInterface $cache
	 *
	 * @throws InvalidBackendException
	 */
	public function setCache(FrontendInterface $cache) {
		parent::setCache($cache);

		/** @var \HDNET\CacheCheck\Domain\Repository\CacheRepository $cacheRepository */
		$cacheRepository = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Domain\\Repository\\CacheRepository');
		$cacheObject = $cacheRepository->findByName($this->cacheIdentifier);

		$backendObjectName = '\\' . ltrim($cacheObject->getOriginalBackend(), '\\');
		$this->originalBackend = new $backendObjectName($this->context, $this->options);
		if (!$this->originalBackend instanceof BackendInterface) {
			throw new InvalidBackendException('"' . $backendObjectName . '" is not a valid cache backend object.', 1216304301);
		}
		$this->originalBackend->setCache($cache);
	}

	/**
	 *
	 * @param string $calledMethod
	 * @param string $entryIdentifier
	 * @param string $data
	 *
	 * @internal param $tStamp
	 * @internal param string $entrySize
	 */
	protected function logEntry($calledMethod, $entryIdentifier = '', $data = '') {
		static $requestHash = NULL;
		if ($requestHash === NULL) {
			$requestHash = uniqid();
		}
		$fieldsValues = array(
			'timestamp'        => GeneralUtility::milliseconds(),
			'request_hash'     => $requestHash,
			'cache_name'       => $this->cacheIdentifier,
			'called_method'    => $calledMethod,
			'entry_identifier' => $entryIdentifier,
			'entry_size'       => strlen($data),
		);
		$this->getDatabaseConnection()
			->exec_INSERTquery('tx_cachecheck_domain_model_log', $fieldsValues);
	}

	/**
	 * Get the database connection
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Setter for compression flags bit
	 *
	 * @param boolean $useCompression
	 *
	 * @return void
	 */
	protected function setCompression($useCompression) {
	}

	/**
	 * Saves data in the cache.
	 *
	 * @param string  $entryIdentifier An identifier for this specific cache entry
	 * @param string  $data            The data to be stored
	 * @param array   $tags            Tags to associate with this cache entry. If the backend does not support tags, this option can be ignored.
	 * @param integer $lifetime        Lifetime of this cache entry in seconds. If NULL is specified, the default lifetime is used. "0" means unlimited lifetime.
	 *
	 * @return void
	 * @throws \TYPO3\CMS\Core\Cache\Exception if no cache frontend has been set.
	 * @throws \TYPO3\CMS\Core\Cache\Exception\InvalidDataException if the data is not a string
	 */
	public function set($entryIdentifier, $data, array $tags = array(), $lifetime = NULL) {
		$this->logEntry('set', $entryIdentifier, $data);
		$this->originalBackend->set($entryIdentifier, $data, $tags, $lifetime);
	}

	/**
	 * Loads data from the cache.
	 *
	 * @param string $entryIdentifier An identifier which describes the cache entry to load
	 *
	 * @return mixed The cache entry's content as a string or FALSE if the cache entry could not be loaded
	 */
	public function get($entryIdentifier) {
		$this->logEntry('get', $entryIdentifier);
		return $this->originalBackend->get($entryIdentifier);
	}

	/**
	 * Checks if a cache entry with the specified identifier exists.
	 *
	 * @param string $entryIdentifier An identifier specifying the cache entry
	 *
	 * @return boolean TRUE if such an entry exists, FALSE if not
	 */
	public function has($entryIdentifier) {
		$this->logEntry('has', $entryIdentifier);
		return $this->originalBackend->has($entryIdentifier);
	}

	/**
	 * Removes all cache entries matching the specified identifier.
	 * Usually this only affects one entry but if - for what reason ever -
	 * old entries for the identifier still exist, they are removed as well.
	 *
	 * @param string $entryIdentifier Specifies the cache entry to remove
	 *
	 * @return boolean TRUE if (at least) an entry could be removed or FALSE if no entry was found
	 */
	public function remove($entryIdentifier) {
		$this->logEntry('remove', $entryIdentifier);
		return $this->originalBackend->remove($entryIdentifier);
	}

	/**
	 * Removes all cache entries of this cache.
	 *
	 * @return void
	 */
	public function flush() {
		$this->logEntry('flush');
		$this->originalBackend->flush();
	}

	/**
	 * Does garbage collection
	 *
	 * @return void
	 */
	public function collectGarbage() {
		$this->logEntry('collectGarbage');
		$this->originalBackend->collectGarbage();
	}

	/**
	 * Freezes this cache backend.
	 *
	 * All data in a frozen backend remains unchanged and methods which try to add
	 * or modify data result in an exception thrown. Possible expiry times of
	 * individual cache entries are ignored.
	 *
	 * On the positive side, a frozen cache backend is much faster on read access.
	 * A frozen backend can only be thawn by calling the flush() method.
	 *
	 * @return void
	 */
	public function freeze() {
		if ($this->originalBackend instanceof FreezableBackendInterface) {
			$this->logEntry('freeze');
			$this->originalBackend->freeze();
		}
	}

	/**
	 * Tells if this backend is frozen.
	 *
	 * @return boolean
	 */
	public function isFrozen() {
		if ($this->originalBackend instanceof FreezableBackendInterface) {
			$this->logEntry('isFrozen');
			return $this->originalBackend->isFrozen();
		}
		return FALSE;
	}

	/**
	 * Loads PHP code from the cache and require_onces it right away.
	 *
	 * @param string $entryIdentifier An identifier which describes the cache entry to load
	 *
	 * @return mixed Potential return value from the include operation
	 */
	public function requireOnce($entryIdentifier) {
		if ($this->originalBackend instanceof PhpCapableBackendInterface) {
			$this->logEntry('requireOnce', $entryIdentifier);
			return $this->originalBackend->requireOnce($entryIdentifier);
		}
		return FALSE;
	}

	/**
	 * Removes all cache entries of this cache which are tagged by the specified tag.
	 *
	 * @param string $tag The tag the entries must have
	 *
	 * @return void
	 */
	public function flushByTag($tag) {
		if ($this->originalBackend instanceof TaggableBackendInterface) {
			$this->logEntry('flushByTag');
			$this->originalBackend->flushByTag($tag);
		}
	}

	/**
	 * Finds and returns all cache entry identifiers which are tagged by the
	 * specified tag
	 *
	 * @param string $tag The tag to search for
	 *
	 * @return array An array with identifiers of all matching entries. An empty array if no entries matched
	 */
	public function findIdentifiersByTag($tag) {
		if ($this->originalBackend instanceof TaggableBackendInterface) {
			$this->logEntry('findIdentifiersByTag');
			return $this->originalBackend->findIdentifiersByTag($tag);
		}
		return array();
	}
}