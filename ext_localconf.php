<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

if (!defined("TYPO3_MODE")) {
	die ("Access denied.");
}

$loader = array(
	'SmartObjects',
	'ContextSensitiveHelps',
	'TypeConverter',
);


// Autoloader
\HDNET\Autoloader\Loader::extLocalconf('HDNET', 'cache_check', $loader);

/** @var \HDNET\CacheCheck\Service\CacheRegistry $cacheRegistry */
$cacheRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\CacheRegistry');
foreach ($cacheRegistry->getCurrent() as $cacheName) {
	\HDNET\CacheCheck\Utility\CacheUtility::enableCheck($cacheName);
}
