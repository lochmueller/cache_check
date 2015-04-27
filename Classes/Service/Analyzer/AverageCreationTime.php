<?php
/**
 * Get average creation time
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Get average creation time
 *
 * @author Tim Lochmüller
 */
class AverageCreationTime extends AbstractAnalyzer {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 */
	public function getKpi(Cache $cache) {
		$queryValues = array(
			'SELECT' => 'AVG(t_set.timestamp - t_has.timestamp) as creation_time',
			'FROM'   => 'tx_cachecheck_domain_model_log t_has, tx_cachecheck_domain_model_log t_set',
			'WHERE'  => "t_has.cache_name = '" . $cache->getName() . "' AND t_has.called_method = 'has' AND t_set.cache_name = '" . $cache->getName() . "' AND t_set.called_method = 'set' AND t_set.entry_size > 0 AND t_has.request_hash = t_set.request_hash AND t_has.entry_identifier = t_set.entry_identifier AND t_has.uid < t_set.uid",
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
