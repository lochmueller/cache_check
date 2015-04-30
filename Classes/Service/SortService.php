<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Service;


use HDNET\CacheCheck\Domain\Model\Cache;

class SortService extends AbstractService {

	/**
	 * First sorting of caches
	 * TODO: add alphabetical order
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	function cmp(Cache $a, Cache $b) {
		$aInt = (int)$a->getIsInAnalyseMode() + (int)$a->getDynamicKpi();
		$bInt = (int)$b->getIsInAnalyseMode() + (int)$b->getDynamicKpi();
		if ($aInt == $bInt) {
			return 0;
		}
		return ($aInt < $bInt) ? 1 : -1;
	}

	/**
	 * Sorting of given array
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public function sortArray($array) {
		usort($array, array(
			$this,
			'cmp'
		));
		return $array;
	}
}