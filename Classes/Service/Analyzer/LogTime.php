<?php
/**
 * Log time
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;

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
	static $internCache = array();

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 * @throws \HDNET\CacheCheck\Exception
	 */
	public function getKpi(Cache $cache) {
		if (isset(self::$internCache[$cache->getName()])) {
			return self::$internCache[$cache->getName()];
		}
		$startTime = $this->getAnalyzer('StartTime');
		$endTime = $this->getAnalyzer('EndTime');
		self::$internCache[$cache->getName()] = $endTime->getKpi($cache) - $startTime->getKpi($cache);
		return self::$internCache[$cache->getName()];
	}

	/**
	 * Format the given KPI
	 *
	 * @param mixed $kpi
	 *
	 * @return string
	 */
	public function getFormat($kpi) {
		return $kpi . ' seconds';
	}
}
