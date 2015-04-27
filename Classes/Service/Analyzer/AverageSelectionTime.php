<?php
/**
 * Average selection time
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Average selection time
 *
 * @author Tim Lochmüller
 */
class AverageSelectionTime extends AbstractAnalyzer {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 * @throws \HDNET\CacheCheck\Exception
	 */
	public function getKpi(Cache $cache) {
		$queryValues = array(
			'SELECT' => 'AVG(t_getAfter.timestamp - t_get.timestamp) as selection_time',
			'FROM'   => 'tx_cachecheck_domain_model_log t_get, tx_cachecheck_domain_model_log t_getAfter',
			'WHERE'  => "t_get.cache_name = '" . $cache->getName() . "' AND t_get.called_method = 'get' AND t_getAfter.cache_name = '" . $cache->getName() . "' AND t_getAfter.called_method = 'getAfter' AND t_get.request_hash = t_getAfter.request_hash AND t_get.entry_identifier = t_getAfter.entry_identifier AND t_get.uid < t_getAfter.uid",
		);
		return (float)$this->getDynamicFromDatabase($queryValues);
	}

	/**
	 * Format the given KPI
	 *
	 * @param mixed $kpi
	 *
	 * @return string
	 */
	public function getFormat($kpi) {
		return round($kpi, 2) . ' milliseconds';
	}
}
