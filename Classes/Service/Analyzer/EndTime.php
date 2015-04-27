<?php
/**
 * End time
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;
use HDNET\CacheCheck\Exception;

/**
 * End time
 *
 * @author Tim Lochmüller
 */
class EndTime extends StartTime {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 * @throws \HDNET\CacheCheck\Exception
	 */
	public function getKpi(Cache $cache) {
		$startTime = $this->getDatabaseConnection()
			->exec_SELECTgetSingleRow('timestamp', 'tx_cachecheck_domain_model_log', 'cache_name = "' . $cache->getName() . '"', '', 'timestamp DESC');
		if (!isset($startTime['timestamp'])) {
			throw new Exception('No start time found', 1236723844);
		}
		return (int)($startTime['timestamp'] / 1000);
	}
}
