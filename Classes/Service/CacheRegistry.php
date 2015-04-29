<?php
/**
 * Class description
 *
 * @package CacheCheck\Service
 * @author  Julian Seitz
 */

namespace HDNET\CacheCheck\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ClassReflection;

/**
 * Class CacheRegistry
 */
class CacheRegistry extends AbstractService {

	/**
	 * Register cache file
	 */
	const FILE_NAME = 'typo3temp/cache_check.txt';

	/**
	 * Not changeable caches
	 *
	 * @var array
	 */
	protected $nonChangeableCaches = array();

	/**
	 * Collect non changeable caches
	 */
	public function __construct() {
		$manager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');

		$classReflection = new ClassReflection(get_class($manager));
		$this->nonChangeableCaches = array_keys($classReflection->getProperty('caches')
			->getValue($manager));
	}

	/**
	 * Get the not changeable caches
	 *
	 * @return array
	 */
	public function getNonChangeableCaches() {
		return $this->nonChangeableCaches;
	}

	/**
	 * Add the given cache to the registry
	 *
	 * @param string $cacheName
	 */
	function add($cacheName) {
		$entries = $this->getCurrent();
		$entries[] = $cacheName;
		GeneralUtility::writeFile($this->getFileName(), serialize($entries));
	}

	/**
	 * Remove the cache with the given key
	 *
	 * @param string $cacheName
	 */
	function remove($cacheName) {
		$entries = $this->getCurrent();
		$key = array_search($cacheName, $entries);
		if ($key !== FALSE) {
			unset($entries[$key]);
			GeneralUtility::writeFile($this->getFileName(), serialize($entries));
		}
	}

	/**
	 * Get the current active caches
	 *
	 * @return array
	 */
	function getCurrent() {
		$activeCaches = unserialize(GeneralUtility::getUrl($this->getFileName()));
		return !is_array($activeCaches) ? array() : $activeCaches;
	}

	/**
	 * returns absolute file name
	 *
	 * @return string
	 */
	protected function getFileName() {
		return GeneralUtility::getFileAbsFileName(self::FILE_NAME);
	}
}
