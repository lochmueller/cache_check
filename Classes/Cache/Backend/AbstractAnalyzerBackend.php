<?php
/**
 * Abstract Analyse backend
 *
 * @package CacheCheck\Cache\Backend
 * @author  Tim Lochmüller
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
abstract class AbstractAnalyzerBackend extends AbstractBackend implements FreezableBackendInterface, PhpCapableBackendInterface, TaggableBackendInterface {

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
	 * Log one entry
	 *
	 * @param string $calledMethod
	 * @param string $entryIdentifier
	 * @param string $data
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
}