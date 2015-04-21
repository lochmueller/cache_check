<?php
/**
 * Stats service for file backend
 *
 * @package CacheCheck\Service\Statistics
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * Stats service for file backend
 *
 * @author Tim Lochmüller
 */
class FileBackend extends SimpleFileBackend {

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
}