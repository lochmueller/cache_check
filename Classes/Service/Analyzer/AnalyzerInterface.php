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
 */
interface AnalyzerInterface
{

    /**
     * Get the given KPI
     *
     * @param Cache $cache
     *
     * @return mixed
     * @throws \HDNET\CacheCheck\Exception
     */
    public function getKpi(Cache $cache);

    /**
     * Format the given KPI
     *
     * @param mixed $kpi
     *
     * @return string
     */
    public function getFormat($kpi);
}
