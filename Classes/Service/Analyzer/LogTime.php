<?php
/**
 * Log time
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Log time
 *
 * @author Tim Lochmüller
 */
class LogTime extends AbstractAnalyzer {

	/**
	 * Internal runtime cache
	 *
	 * @var array
	 */
	static $internalCache = array();

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 * @throws \HDNET\CacheCheck\Exception
	 */
	public function getKpi(Cache $cache) {
		if (isset(self::$internalCache[$cache->getName()])) {
			return self::$internalCache[$cache->getName()];
		}
		$startTime = $this->getAnalyzer('StartTime');
		$endTime = $this->getAnalyzer('EndTime');
		self::$internalCache[$cache->getName()] = $endTime->getKpi($cache) - $startTime->getKpi($cache);
		return self::$internalCache[$cache->getName()];
	}

	/**
	 * Format the given KPI
	 *
	 * @param mixed $kpi
	 *
	 * @return string
	 */
	public function getFormat($kpi) {
		$formatService = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\FormatService');
		return $formatService->formatSeconds($kpi);
	}
}
