<?php
/**
 * KPI calculation
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service;

use HDNET\CacheCheck\Domain\Model\Cache;
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

		$startTime = $databaseConnection->exec_SELECTgetSingleRow('timestamp', $table, $where, '', 'timestamp ASC');
		$startTime = (int)($startTime['timestamp'] / 1000);
		$endTime = $databaseConnection->exec_SELECTgetSingleRow('timestamp', $table, $where, '', 'timestamp DESC');
		$endTime = (int)($endTime['timestamp'] / 1000);
		$minutes = ($endTime - $startTime) / 60;

		$countHas = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');
		$countGet = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');
		$countSet = $databaseConnection->exec_SELECTcountRows('*', $table, $where . ' AND called_method = "has"');





		$kpi = array(
			'startTime'            => date('d.m.Y H:i:s', $startTime),
			'averageCreateTime'    => $this->getAverageCreationTime($cache->getName()),
			'averageSelectionTime' => 0,
			'averageLivetime'      => 0,# $this->getAverageLiveTime($cache->getName()),
			'hitRate'              => '',
			'missRate'             => '',
			'hasPerMinute'         => $countHas / $minutes,
			'getPerMinute'         => $countGet / $minutes,
			'setPerMinute'         => $countSet / $minutes,
			'endTime'              => date('d.m.Y H:i:s', $endTime),
			'logTime'              => $minutes . ' minutes',
		);

		return $kpi;
	}

	/**
	 *
	 * gets and calculates difference in timestamp of has and set entries with the request hash
	 *
	 * @param $cacheName          string
	 * @return float|bool
	 */
	protected function getAverageCreationTime($cacheName) {
		$databaseConnection = $this->getDatabaseConnection();
		$query = "SELECT AVG(t_set.timestamp - t_has.timestamp) as creation_time FROM tx_cachecheck_domain_model_log t_has, tx_cachecheck_domain_model_log t_set WHERE t_has.cache_name = '" . $cacheName . "' AND t_has.called_method = 'has' AND t_set.cache_name = '" . $cacheName . "' AND t_set.called_method = 'set' AND t_set.entry_size > 0 AND t_has.request_hash = t_set.request_hash AND t_has.entry_identifier = t_set.entry_identifier AND t_has.uid < t_set.uid";
		$result = $databaseConnection->sql_fetch_row($databaseConnection->sql_query($query));
		if ($result[0]) {
			return $result[0];
		}
		return NULL;
	}

	protected function getAverageLiveTime($cacheName) {
		$databaseConnection = $this->getDatabaseConnection();
		$query = "SELECT AVG(t_remove.timestamp - t_set.timestamp) as creation_time FROM tx_cachecheck_domain_model_log t_set, tx_cachecheck_domain_model_log t_remove WHERE t_set.cache_name = '" . $cacheName . "' AND t_set.called_method = 'set' AND t_remove.cache_name = '" . $cacheName . "' AND t_remove.called_method = 'remove' AND t_remove.entry_size > 0 AND t_set.entry_identifier = t_remove.entry_identifier AND t_set.uid < t_remove.uid";
		$result = $databaseConnection->sql_fetch_row($databaseConnection->sql_query($query));
		if ($result[0]) {
			return $result[0];
		}
		return NULL;
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
