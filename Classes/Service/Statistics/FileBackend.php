<?php
/**
 * Stats service for file backend
 *
 * @package CacheCheck\Service\Statistics
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Stats service for file backend
 *
 * @author Tim Lochmüller
 */
class FileBackend extends SimpleFileBackend {

	const EXPIRYTIME_LENGTH = 14;
	const DATASIZE_DIGITS = 10;

	/**
	 * Get the number of tags
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countTags(Cache $cache) {
		return 'Not implement yet';
	}

	/**
	 * Returns the average left lifetime of the cache entries
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getExpires(Cache $cache) {
		$cacheFileNames = glob(GeneralUtility::getFileAbsFileName($this->getCacheDirectory($cache) . '*'));
		$expireTimes = array();
		foreach ($cacheFileNames as $cacheFileName) {
			$index = (int)file_get_contents($cacheFileName, NULL, NULL, (filesize($cacheFileName) - self::DATASIZE_DIGITS), self::DATASIZE_DIGITS);
			$expiryTime = (int)file_get_contents($cacheFileName, NULL, NULL, $index, self::EXPIRYTIME_LENGTH);
			$expireTimes[] = $expiryTime;
		}
		if ($expireTimes) {
			return array_sum($expireTimes) / count($expireTimes);
		}
		return NULL;
	}
}