<?php
/**
 * Cache utility
 *
 * @package CacheCheck\Utility
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Utility;

/**
 * Cache utility
 *
 * @author Tim Lochmüller
 */
class CacheUtility {

	/**
	 * Enable the cache for the analysis
	 *
	 * @param string $cacheName
	 *
	 * @return void
	 */
	static public function enableCheck($cacheName) {
		$backend = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'];
		if (trim($backend) == '' || !class_exists($backend)) {
			$backend = 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend';
		}
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['originalBackend'] = $backend;
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'] = 'HDNET\\CacheCheck\\Cache\\Backend\\CacheAnalyzerBackend';
	}
}
