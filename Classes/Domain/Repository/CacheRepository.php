<?php
/**
 * Cache Repository
 *
 * @package CacheCheck\Domain\Repository
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Repository;

use HDNET\CacheCheck\Domain\Model\Cache;
use HDNET\CacheCheck\Service\SortService;
use TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;

/**
 * Cache Repository
 *
 * @author Tim Lochmüller
 */
class CacheRepository
{

    /**
     * Find all caches
     *
     * @return array
     */
    public function findAll()
    {
        $sortService = new SortService();
        $caches = [];
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'] as $name => $configuration) {
            $caches[] = $this->mapCacheConfigurationIntoModel($name, $configuration);
        }
        return $sortService->sortArray($caches);
    }

    /**
     * Find the cache with the given name
     *
     * @param string $cacheName
     *
     * @return Cache|NULL
     */
    public function findByName($cacheName)
    {
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName])) {
            return null;
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
    protected function mapCacheConfigurationIntoModel($cacheName, $configuration)
    {
        $cache = new Cache();
        $cache->setName($cacheName);
        $cache->setFrontend(isset($configuration['frontend']) && class_exists($configuration['frontend']) ? $configuration['frontend'] : VariableFrontend::class);
        $cache->setBackend(isset($configuration['backend']) && class_exists($configuration['backend']) ? $configuration['backend'] : Typo3DatabaseBackend::class);
        $cache->setOriginalBackend(isset($configuration['originalBackend']) && class_exists($configuration['originalBackend']) ? $configuration['originalBackend'] : '');
        $cache->setOptions(isset($configuration['options']) ? $configuration['options'] : []);
        $cache->setGroups(isset($configuration['groups']) ? $configuration['groups'] : ['all']);
        return $cache;
    }
}
