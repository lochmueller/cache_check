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
 * @todo Move dynamic KPI to separate classes
 */
class AverageCreationTime implements AnalyzerInterface {

	/**
	 * Get the given KPI
	 *
	 * @param Cache $cache
	 *
	 * @return mixed
	 */
	public function getKpi(Cache $cache) {

	}
}
