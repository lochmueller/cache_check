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
foreach ($cacheRegistry->getCurrent() as $cacheName) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['originalBackend'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'];
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'] = 'HDNET\\CacheCheck\\Cache\\Backend\\CacheAnalyzerBackend';
}
