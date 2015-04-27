<?php
/**
 * Stats service interface
 *
 * @package CacheCheck\Service\Statistics
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Stats service interface
 *
 * @author Tim Lochmüller
 */
interface StatisticsInterface extends SingletonInterface {

	/**
	 * Get the number of entries
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countEntries(Cache $cache);

	/**
	 * Get the number of tags
	 *
	 * @param Cache $cache
	 *
	 * @return int
	 */
	public function countTags(Cache $cache);

	/**
	 * Get the size of the cache in byte
	 *
	 * @param Cache $cache
	 *
	 * @return float
	 */
	public function getSize(Cache $cache);

	/**
	 * Returns the age of found entries in seconds
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getAge(Cache $cache);

	/**
	 * Returns the left lifetime of the cache entry
	 *
	 * @param Cache $cache
	 *
	 * @return int|null
	 */
	public function getExpires(Cache $cache);
}
