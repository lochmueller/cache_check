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
use TYPO3\CMS\Core\Database\DatabaseConnection;
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
	 * @todo implement in general for all backends
	 */
	public function getStatic(Cache $cache) {
		$backendParts = GeneralUtility::trimExplode('\\', $cache->getRealBackend(), TRUE);
		$statsBackend = 'HDNET\\CacheCheck\\Service\\Statistics\\' . $backendParts[sizeof($backendParts) - 1];

		if (!class_exists($statsBackend)) {
			return FALSE;
		}
		/** @var StatisticsInterface $statsBackendObject */
		$statsBackendObject = GeneralUtility::makeInstance($statsBackend);

		$size = $statsBackendObject->getSize($cache);
		$entryCount = $statsBackendObject->countEntries($cache);
		$tagCount = $statsBackendObject->countTags($cache);
		return array(
			'cacheEntriesCount'    => $entryCount,
			'allEntrySizeByte'     => $size,
			'averageEntrySizeByte' => $entryCount === 0 ? 0 : $size / $entryCount,
			'differentTagsCount'   => $tagCount,
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
		$databaseConnection = $this->getDatabaseConnection();
		$where = 'cache_name = "' . $cache->getName() . '"';
		$table = 'tx_cachecheck_domain_model_log';
		if ($databaseConnection->exec_SELECTcountRows('*', $table, $where) <= 0) {
			return FALSE;
		}

		// @todo move to separate class
		$startTime = $databaseConnection->exec_SELECTgetSingleRow('timestamp', $table, $where, '', 'timestamp ASC');
		$startTime = (int)($startTime['timestamp'] / 1000);
		$endTime = $databaseConnection->exec_SELECTgetSingleRow('timestamp', $table, $where, '', 'timestamp DESC');
		$endTime = (int)($endTime['timestamp'] / 1000);
		$minutes = ($endTime - $startTime) / 60;

		// @todo move to separate class
		$countHas = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');

		// @todo move to separate class
		$countGet = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');

		// @todo move to separate class
		$countSet = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');

		$kpi = array(
			'startTime'    => date('d.m.Y H:i:s', $startTime),
			'hasPerMinute' => $countHas / $minutes,
			'getPerMinute' => $countGet / $minutes,
			'setPerMinute' => $countSet / $minutes,
			'endTime'      => date('d.m.Y H:i:s', $endTime),
			'logTime'      => $minutes . ' minutes',
		);

		// new mechanism by class
		$kpiClasses = array(
			'AverageCreationTime',
			'AverageSelectionTime',
			'HitRate',
			'MissRate',
		);

		foreach ($kpiClasses as $class) {
			/** @var AnalyzerInterface $kpiObject */
			$kpiObject = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\Analyzer\\' . $class);
			try {
				$kpiValue = $kpiObject->getKpi($cache);
				$kpiValue = $kpiObject->getFormat($kpiValue);
			} catch (Exception $ex) {
				$kpiValue = 'NaN';
			}
			$kpi[lcfirst($class)] = $kpiValue;
		}

		return $kpi;
	}

	/**
	 * Get database connection
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}
