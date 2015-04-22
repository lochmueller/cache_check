<?php
/**
 * Miss rate
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Miss rate
 *
 * @author Tim Lochmüller
 */
class MissRate extends AbstractAnalyzer {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 * @throws \HDNET\CacheCheck\Exception
	 */
	public function getKpi(Cache $cache) {
		/** @var AnalyzerInterface $hitRate */
		$hitRate = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\Analyzer\\HitRate');
		return 1 - $hitRate->getKpi($cache);
	}

	/**
	 * Format the given KPI
	 *
	 * @param mixed $kpi
	 *
	 * @return string
	 */
	public function getFormat($kpi) {
		return round($kpi * 100, 2) . '%';
	}
}
