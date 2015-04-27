<?php
/**
 * Stats service for simple files
 *
 * @package CacheCheck\Service\Statistics
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Stats service for simple files
 *
 * @author Tim Lochmüller
 */
class SimpleFileBackend implements StatisticsInterface {

	/**
	 * Get the number of entries
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countEntries(Cache $cache) {
		return sizeof(glob(GeneralUtility::getFileAbsFileName($this->getCacheDirectory($cache) . '*')));
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

		return $this->getFolderSize(GeneralUtility::getFileAbsFileName($this->getCacheDirectory($cache)));
	}

	/**
	 * Get the number of tags
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countTags(Cache $cache) {
		return 0;
	}

	/**
	 * Get the foldersize
	 *
	 * @param string $path
	 *
	 * @return int
	 */
	protected function getFolderSize($path) {
		$totalSize = 0;
		$files = scandir($path);
		foreach ($files as $t) {
			if ($t !== '.' && $t !== '..') {
				$currentFile = $path . $t;
				if (is_dir($currentFile)) {
					$totalSize += $this->getFolderSize($currentFile);
				} else {
					$totalSize += (int)filesize($currentFile);
				}
			}
		}

		return $totalSize;
	}

	/**
	 * Get the cache directory
	 *
	 * @param Cache $cache
	 *
	 * @return string
	 */
	protected function getCacheDirectory(Cache $cache) {
		$codeOrData = $cache->getFrontend() === 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend' ? 'Code' : 'Data';
		return 'typo3temp/Cache/' . $codeOrData . '/' . $cache->getName() . '/';
	}

	/**
	 * Returns the age of found entries in seconds
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getAge(Cache $cache) {
		$cacheFiles = glob(GeneralUtility::getFileAbsFileName($this->getCacheDirectory($cache) . '*'));
		if (!$cacheFiles) {
			return NULL;
		}
		foreach ($cacheFiles as $key => $cacheFile) {
			$cacheFiles[$key] = time() - filectime($cacheFile);
		}
		return intval(array_sum($cacheFiles) / count($cacheFiles));
	}

	/**
	 * Returns the left lifetime of the cache entry
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getExpires(Cache $cache) {
		return NULL;
	}
}