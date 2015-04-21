<?php
/**
 * Analyzer of the log
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Analyzer of the log
 *
 * @author Tim Lochmüller
 * @todo   Move dynamic KPI to separate classes
 */
interface AnalyzerInterface {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 */
	public function getKpi(Cache $cache);
}
