<?php
/**
 * KPI calculation
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service;

use HDNET\CacheCheck\Domain\Model\Cache;
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
		if ($cache->getRealBackend() !== 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend') {
			return FALSE;
		}

		$databaseConnection = $this->getDatabaseConnection();
		$cacheTable = 'cf_' . $cache->getName();
		$tagsTable = 'cf_' . $cache->getName() . '_tags';

		$entryCount = $databaseConnection->exec_SELECTcountRows('*', $cacheTable, '1=1');

		$size = 0;
		if ($entryCount > 0) {
			$query = $databaseConnection->admin_query('SELECT ((data_length + index_length) / 1024 / 1024) "size" FROM information_schema.TABLES WHERE table_schema = "' . $GLOBALS['TYPO3_CONF_VARS']['DB']['database'] . '" AND table_name = "' . $cacheTable . '"');
			$info = $databaseConnection->sql_fetch_assoc($query);
			$size = $info['size'];
		}
		$kpi = array(
			'cacheEntriesCount'  => $entryCount,
			'allEntrySizeMb'     => $size,
			'averageEntrySizeMb' => $entryCount === 0 ? 0 : $size / $entryCount,
			'differentTagsCount' => $databaseConnection->exec_SELECTcountRows('DISTINCT tag', $tagsTable, '1=1'),
		);

		return $kpi;
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

		// @todo implement

		$kpi = array(
			'startTime'            => date('d.m.Y H:i:s', $startTime),
			'averageCreateTime'    => 0,
			'averageSelectionTime' => 0,
			'averageLivetime'      => 0,
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
	 * Get databsae connection
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}
