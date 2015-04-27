<?php
/**
 * Analyzer abstraction
 *
 * @package CacheCheck\Service\Analyzer
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Analyzer;

use HDNET\CacheCheck\Exception;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Analyzer abstraction
 *
 * @author Tim Lochmüller
 */
abstract class AbstractAnalyzer implements AnalyzerInterface, SingletonInterface {

	/**
	 * get dynamic values from database
	 *
	 * @param array $queryValues
	 *
	 * @return string|null
	 */
	protected function getDynamicFromDatabase(array $queryValues) {
		$databaseConnection = $this->getDatabaseConnection();
		$checkValues = array(
			'SELECT',
			'FROM',
			'WHERE',
			'GROUPBY',
			'ORDERBY',
			'LIMIT'
		);
		foreach ($checkValues as $name) {
			if (!array_key_exists($name, $queryValues)) {
				$queryValues[$name] = '';
			}
		}

		$res = $databaseConnection->exec_SELECT_queryArray($queryValues);
		if (!$res) {
			return new Exception('Invalid SQL Query: ' . var_export($checkValues, TRUE), 2346273846783);
		}
		$result = $databaseConnection->sql_fetch_row($res);
		if (!isset($result[0])) {
			return new Exception('No single value is found in the SQL Query', 324528943578);
		}
		return $result[0];
	}

	/**
	 * Get an analyzer
	 *
	 * @param string $name
	 *
	 * @return AnalyzerInterface
	 */
	protected function getAnalyzer($name) {
		return GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\Analyzer\\' . $name);
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
