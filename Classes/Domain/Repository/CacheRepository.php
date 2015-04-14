<?php
/**
 * Cache Repository
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Repository;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Cache Repository
 *
 * @author Tim Lochmüller
 */
class CacheRepository {

	/**
	 * Find all caches
	 *
	 * @return array
	 */
	public function findAll() {
		$caches = array();
		foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'] as $name => $configuration) {
			$caches[] = $this->mapCacheConfigurationIntoModel($name, $configuration);
		}
		return $caches;
	}

	/**
	 * Find the cache with the given name
	 *
	 * @param string $cacheName
	 *
	 * @return Cache|NULL
	 */
	public function findByName($cacheName) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName])) {
			return NULL;
		}
		return $this->mapCacheConfigurationIntoModel($cacheName, $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]);
	}

	/**
	 * Map the given configuration into a cache model
	 *
	 * @param string $cacheName
	 * @param array  $configuration
	 *
	 * @return Cache
	 */
	protected function mapCacheConfigurationIntoModel($cacheName, $configuration) {
		$cache = new Cache();
		$cache->setName($cacheName);
		return $cache;
	}
}
