<?php
/**
 * Analyse backend
 *
 * @package CacheCheck\Cache\Backend
 * @author  Julian Seitz
 */

namespace HDNET\CacheCheck\Cache\Backend;

use TYPO3\CMS\Core\Cache\Backend\FreezableBackendInterface;
use TYPO3\CMS\Core\Cache\Backend\PhpCapableBackendInterface;
use TYPO3\CMS\Core\Cache\Backend\TaggableBackendInterface;

/**
 * Class CacheAnalyzerBackend
 */
class CacheAnalyzerBackend extends AbstractAnalyzerBackend implements FreezableBackendInterface, PhpCapableBackendInterface, TaggableBackendInterface {

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
		$data = $this->originalBackend->get($entryIdentifier);
		$this->logEntry('getAfter', $entryIdentifier);
		if ($data !== FALSE) {
			$this->logEntry('getTRUE', $entryIdentifier);
		}
		return $data;
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
		$data = $this->originalBackend->has($entryIdentifier);
		if ($data !== FALSE) {
			$this->logEntry('hasTRUE', $entryIdentifier);
		}
		return $data;
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
			$data = $this->originalBackend->requireOnce($entryIdentifier);
			if ($data !== FALSE) {
				$this->logEntry('requireOnceTRUE', $entryIdentifier);
			}
			return $data;
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