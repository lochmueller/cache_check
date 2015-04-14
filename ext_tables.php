<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

if (!defined("TYPO3_MODE")) {
	die ("Access denied.");
}
if (TYPO3_MODE === 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule('HDNET.' . $_EXTKEY, 'tools', 'CacheCheck', '', array('CacheCheck' => 'getCaches,list,startAnalyzing,stopAnalyzing'), array(
		'access' => 'user,group',
		'icon'   => 'EXT:cache_check/ext_icon.gif',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
	));
}