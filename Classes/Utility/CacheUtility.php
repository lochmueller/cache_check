<?php
/**
 * Cache utility
 *
 * @package CacheCheck\Utility
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Utility;

use HDNET\CacheCheck\Cache\Backend\CacheAnalyzerBackend;
use TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend;

/**
 * Cache utility
 *
 * @author Tim Lochmüller
 */
class CacheUtility
{

    /**
     * Enable the cache for the analysis
     *
     * @param string $cacheName
     *
     * @return void
     */
    public static function enableCheck($cacheName)
    {
        $backend = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'];
        if (trim($backend) == '' || !class_exists($backend)) {
            $backend = Typo3DatabaseBackend::class;
        }
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['originalBackend'] = $backend;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'] = CacheAnalyzerBackend::class;
    }
}
