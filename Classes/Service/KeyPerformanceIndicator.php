<?php
/**
 * KPI calculation
 *
 * @package CacheCheck\Service
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service;

use HDNET\CacheCheck\Domain\Model\Cache;
use HDNET\CacheCheck\Exception;
use HDNET\CacheCheck\Service\Analyzer\AnalyzerInterface;
use HDNET\CacheCheck\Service\Statistics\StatisticsInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * KPI calculation
 *
 * @author Tim Lochmüller
 */
class KeyPerformanceIndicator extends AbstractService {

	/**
	 * Get the current instance
	 *
	 * @return KeyPerformanceIndicator
	 */
	public static function getInstance() {
		return GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\KeyPerformanceIndicator');
	}

	/**
	 * Get static cache information
	 *
	 * @param Cache $cache
	 *
	 * @return array
	 */
	public function getStatic(Cache $cache) {
		$formatService = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\FormatService');
		$backendParts = GeneralUtility::trimExplode('\\', $cache->getRealBackend(), TRUE);
		$className = 'HDNET\\CacheCheck\\Service\\Statistics\\' . $backendParts[sizeof($backendParts) - 1];

		if (!class_exists($className)) {
			return FALSE;
		}

		/** @var StatisticsInterface $statsBackend */
		$statsBackend = GeneralUtility::makeInstance($className);
		$size = $statsBackend->getSize($cache);
		$entryCount = $statsBackend->countEntries($cache);
		return array(
			'cacheEntriesCount'     => $entryCount,
			'allEntrySizeByte'      => $formatService->formatBytes($size),
			'averageEntrySizeByte'  => $formatService->formatBytes($entryCount === 0 ? 0 : $size / $entryCount),
			'differentTagsCount'    => $statsBackend->countTags($cache),
			'averageAgeOfCache'     => $formatService->formatSeconds($statsBackend->getAge($cache)),
			'averageExpiresOfCache' => $formatService->formatSeconds($statsBackend->getExpires($cache)),
		);
	}

	/**
	 * Get dynamic cache information
	 *
	 * @param Cache $cache
	 *
	 * @return array
	 */
	public function getDynamic(Cache $cache) {
		$kpiClasses = array(
			'CountLogEntries',
			'StartTime',
			'EndTime',
			'LogTime',
			'AverageCreationTime',
			'AverageSelectionTime',
			'HitRate',
			'MissRate',
			'HasPerSecond',
			'GetPerSecond',
			'SetPerSecond',
		);

		$kpi = array();
		foreach ($kpiClasses as $i => $class) {
			/** @var AnalyzerInterface $kpiObject */
			$kpiObject = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\Analyzer\\' . $class);
			try {
				$kpiValue = $kpiObject->getKpi($cache);
				if ($i === 0 && $kpiValue === 0) {
					return FALSE;
				}
				$kpiValue = $kpiObject->getFormat($kpiValue);
			} catch (Exception $ex) {
				$kpiValue = 'NaN';
			}
			$kpi[lcfirst($class)] = $kpiValue;
		}

		return $kpi;
	}
}
