<?php
/**
 * Stats service for TYPO3 database
 *
 * @package CacheCheck\Service\Statistics
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Stats service for TYPO3 database
 *
 * @author Tim Lochmüller
 */
class Typo3DatabaseBackend implements StatisticsInterface {

	/**
	 * Get the number of entries
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countEntries(Cache $cache) {
		return (int)$this->getDatabaseConnection()
			->exec_SELECTcountRows('*', 'cf_' . $cache->getName(), '1=1');
	}

	/**
	 * Get the size of the cache in byte
	 *
	 * @param Cache $cache
	 *
	 * @return float
	 */
	public function getSize(Cache $cache) {
		if ($this->countEntries($cache) === 0) {
			return 0.0;
		}

		$databaseConnection = $this->getDatabaseConnection();
		$query = 'SELECT data_length FROM information_schema.TABLES WHERE table_schema="' . $GLOBALS['TYPO3_CONF_VARS']['DB']['database'] . '" AND table_name="cf_' . $cache->getName() . '"';
		$res = $databaseConnection->sql_query($query);
		$info = $databaseConnection->sql_fetch_assoc($res);
		return (float)$info['data_length'];
	}

	/**
	 * Get the number of tags
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countTags(Cache $cache) {
		return (int)$this->getDatabaseConnection()
			->exec_SELECTcountRows('DISTINCT tag', 'cf_' . $cache->getName() . '_tags', '1=1');
	}

	/**
	 * Get database connection
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Returns the age of found entries in seconds
	 *
	 * @param Cache $cache
	 *
	 * @return null
	 */
	public function getAge(Cache $cache) {
		return NULL;
	}

	/**
	 * Returns the age of found entries in seconds// DOES NOT. RETURNS LEFT LIFETIME
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getExpires(Cache $cache) {
		if ($this->countEntries($cache) === 0) {
			return NULL;
		}
		$databaseConnection = $this->getDatabaseConnection();
		$query = "SELECT AVG(CAST(expires AS SIGNED INTEGER) - UNIX_TIMESTAMP()) as life_time_left FROM cf_" . $cache->getName() . " WHERE expires > UNIX_TIMESTAMP() AND expires < 2145909600";
		$res = $databaseConnection->sql_query($query);
		$info = $databaseConnection->sql_fetch_assoc($res);
		return (int)$info['life_time_left'];
	}
}