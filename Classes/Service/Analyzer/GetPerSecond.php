<?php
/**
 * Get per second
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Domain\Model\Cache;
use HDNET\CacheCheck\Exception;

/**
 * Get per second
 *
 * @author Tim Lochmüller
 */
class GetPerSecond extends AbstractAnalyzer
{

    /**
     * Get the given KPI
     *
     * @param Cache $cache
     *
     * @return mixed
     * @throws \HDNET\CacheCheck\Exception
     */
    public function getKpi(Cache $cache)
    {
        $countHas = $this->getDatabaseConnection()
            ->exec_SELECTcountRows('*', 'tx_cachecheck_domain_model_log', 'cache_name = "' . $cache->getName() . '"' . ' AND called_method = "get"');
        $logTime = $this->getAnalyzer('LogTime');
        if ($logTime->getKpi($cache) == 0) {
            throw new Exception('No valid log time found', 123627467234);
        }
        return $countHas / $logTime->getKpi($cache);
    }

    /**
     * Format the given KPI
     *
     * @param mixed $kpi
     *
     * @return string
     */
    public function getFormat($kpi)
    {
        return $kpi;
    }
}
