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
\HDNET\Autoloader\Loader::extTables('HDNET', 'cache_check', $loader);

if (TYPO3_MODE === 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule('HDNET.' . $_EXTKEY, 'tools', 'CacheCheck', '', array('CacheCheck' => 'list,start,stop,delete,flush'), array(
		'access' => 'user,group',
		'icon'   => 'EXT:cache_check/ext_icon.png',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
	));
}