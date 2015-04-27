<?php
/**
 * Hit rate
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Hit rate
 *
 * @author Tim Lochmüller
 */
class HitRate extends AbstractAnalyzer {

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
			'SELECT' => 'COUNT(DISTINCT allTrue.uid) / COUNT(DISTINCT hasGetRequireOnce.uid) as hitRate',
			'FROM'   => "(SELECT uid,request_hash,entry_identifier,called_method FROM tx_cachecheck_domain_model_log WHERE cache_name = '" . $cache->getName() . "' AND called_method IN ('has', 'get', 'requireOnce')) hasGetRequireOnce, (SELECT uid,request_hash,entry_identifier,called_method FROM tx_cachecheck_domain_model_log WHERE cache_name = '" . $cache->getName() . "' AND called_method IN ('hasTRUE', 'getTRUE', 'requireOnceTRUE')) allTrue",
			'WHERE'  => 'hasGetRequireOnce.entry_identifier = allTrue.entry_identifier AND hasGetRequireOnce.request_hash = allTrue.request_hash AND hasGetRequireOnce.uid < allTrue.uid',
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
		return round($kpi * 100, 2) . '%';
	}
}
