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
			'averageCreationTime'  => $this->getAverageCreationTime($cache->getName()) . ' milliseconds',
			'averageSelectionTime' => $this->getAverageSelectionTime($cache->getName()) . ' milliseconds',
			'hitRate'              => $this->getHitRate($cache->getName()) * 100 . '%',
			'missRate'             => (1 - $this->getHitRate($cache->getName())) * 100 . '%',
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
	 *
	 * @return float|bool
	 */
	protected function getAverageCreationTime($cacheName) {
		$databaseConnection = $this->getDatabaseConnection();
		$query = "SELECT AVG(t_set.timestamp - t_has.timestamp) as creation_time FROM tx_cachecheck_domain_model_log t_has, tx_cachecheck_domain_model_log t_set WHERE t_has.cache_name = '" . $cacheName . "' AND t_has.called_method = 'has' AND t_set.cache_name = '" . $cacheName . "' AND t_set.called_method = 'set' AND t_set.entry_size > 0 AND t_has.request_hash = t_set.request_hash AND t_has.entry_identifier = t_set.entry_identifier AND t_has.uid < t_set.uid";
		$result = $databaseConnection->sql_fetch_row($databaseConnection->sql_query($query));
		if ($result[0]) {
			return $result[0];
		}
		return 0;
	}

	/**
	 * returns difference in timestamp before and after original BE method is called.
	 *
	 * @param $cacheName string
	 *
	 * @return int
	 */
	protected function getAverageSelectionTime($cacheName) {
		$databaseConnection = $this->getDatabaseConnection();
		$query = "SELECT AVG(t_getAfter.timestamp - t_get.timestamp) as selection_time FROM tx_cachecheck_domain_model_log t_get, tx_cachecheck_domain_model_log t_getAfter WHERE t_get.cache_name = '" . $cacheName . "' AND t_get.called_method = 'get' AND t_getAfter.cache_name = '" . $cacheName . "' AND t_getAfter.called_method = 'getAfter' AND t_get.request_hash = t_getAfter.request_hash AND t_get.entry_identifier = t_getAfter.entry_identifier AND t_get.uid < t_getAfter.uid";
		$result = $databaseConnection->sql_fetch_row($databaseConnection->sql_query($query));
		if ($result[0]) {
			return $result[0];
		}
		return 0;
	}

	/**
	 * @param $cacheName string
	 *
	 * @return int
	 */
	protected function getHitRate($cacheName) {
		$databaseConnection = $this->getDatabaseConnection();


		$query = array(
			'SELECT'  => 'COUNT(DISTINCT allTrue.uid) / COUNT(DISTINCT hasGetRequireOnce.uid) as hitRate',
			'FROM'    => "(SELECT uid,request_hash,entry_identifier,called_method FROM tx_cachecheck_domain_model_log WHERE cache_name = '" . $cacheName . "' AND called_method IN ('has', 'get', 'requireOnce')) hasGetRequireOnce, (SELECT uid,request_hash,entry_identifier,called_method FROM tx_cachecheck_domain_model_log WHERE cache_name = '" . $cacheName . "' AND called_method IN ('hasTRUE', 'getTRUE', 'requireOnceTRUE')) allTrue",
			'WHERE'   => 'hasGetRequireOnce.entry_identifier = allTrue.entry_identifier AND hasGetRequireOnce.request_hash = allTrue.request_hash AND hasGetRequireOnce.uid < allTrue.uid',
			'GROUPBY' => '',
			'ORDERBY' => '',
			'LIMIT'   => '',
		);

		$result = $databaseConnection->sql_fetch_row($databaseConnection->exec_SELECT_queryArray($query));
		if ($result[0]) {
			return $result[0];
		}
		return 0;
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
