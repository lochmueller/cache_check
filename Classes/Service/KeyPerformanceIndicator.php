<?php
/**
 * KPI calculation
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * KPI calculation
 *
 * @author Tim Lochmüller
 */
class KeyPerformanceIndicator implements SingletonInterface {

	/**
	 * Get the current instance
	 *
	 * @return KeyPerformanceIndicator
	 */
	public static function getInstance() {
		return GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\KeyPerformanceIndicator');
	}

	/**
	 * @param Cache $cache
	 *
	 * @return array
	 */
	public function getStatic(Cache $cache) {

		// @todo implement

		$kpi = array(
			'cacheEntries'     => 0,
			'averageEntrySize' => 0,
			'tags'             => '',
		);

		return $kpi;
	}

	/**
	 * @param Cache $cache
	 *
	 * @return array
	 */
	public function getDynamic(Cache $cache) {

		// @todo implement

		return FALSE;

		$kpi = array(
			'startTime'            => '',
			'averageCreateTime'    => 0,
			'averageSelectionTime' => 0,
			'averageLivetime'      => 0,
			'hitRate'              => '',
			'missRate'             => '',
			'hasPerMinute'         => '',
			'getPerMinute'         => '',
			'setPerMinute'         => '',
			'endTime'              => '',
			'logTime'              => '',
		);

		return $kpi;
	}
}
