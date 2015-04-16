<?php
/**
 * Cache utility
 *
 * @package Hdnet
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
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['originalBackend'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'];
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'] = 'HDNET\\CacheCheck\\Cache\\Backend\\CacheAnalyzerBackend';
	}
}
