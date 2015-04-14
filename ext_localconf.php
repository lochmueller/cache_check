<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

if (!defined("TYPO3_MODE")) {
	die ("Access denied.");
}

/** @var \HDNET\CacheCheck\Service\CacheRegistry $cacheRegistry */
$cacheRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\CacheRegistry');
$cacheConfigurations = &$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
foreach ($cacheRegistry->getCurrent() as $cacheName) {
	$cacheConfigurations[$cacheName]['originalBackend'] = $cacheConfigurations[$cacheName]['backend'];
	$cacheConfigurations[$cacheName]['backend'] = 'HDNET\\CacheCheck\\Cache\\Backend\\CacheAnalyzerBackend';
}
