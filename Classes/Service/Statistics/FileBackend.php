<?php
/**
 * @todo    General file information
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Service\Statistics;

use HDNET\CacheCheck\Domain\Model\Cache;

/**
 * @todo   General class information
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