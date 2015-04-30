<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Service;


class SortService extends AbstractService {

	/**
	 * First sorting by cache analysis
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	function compareCaches($a, $b) {
		if ($a->getIsInAnalyseMode AND $b->getIsInAnalyseMode) {
			return 0;
		}
		if ($a->getIsInAnalyseMode AND !$b->getIsInAnalyseMode) {
			return 1;
		}
		return -1;
	}

	/**
	 * Sorting of given array
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public function sortArray($array) {
		usort($array, 'compareCaches');
		return $array;
	}
}