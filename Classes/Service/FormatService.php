<?php
/**
 * Class description
 *
 * @package CacheCheck\Service
 * @author  Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FormatService
 */
class FormatService extends AbstractService {

	const MINUTE = 60;

	const HOUR = 3600;

	const DAY = 86400;

	const YEAR = 31536000;

	/**
	 * Splits given time value(in seconds) into years, days, hours, minutes, seconds
	 *
	 * @param        $seconds
	 *
	 * @return string
	 */
	public function formatSeconds($seconds) {
		if (!is_int($seconds)) {
			return 'NaN';
		}
		$return = array();

		$slots = array(
			'year'   => self::YEAR,
			'day'    => self::DAY,
			'hour'   => self::HOUR,
			'minute' => self::MINUTE,
		);
		foreach ($slots as $label => $secondSlot) {
			$fullInt = floor($seconds / $secondSlot);
			if ($fullInt > 0) {
				$return[] = $fullInt . ' ' . $label;
			}
			$seconds = $seconds % $secondSlot;
		}

		// seconds
		if ($seconds >= 0) {
			$return[] = $seconds . ' seconds';
		}

		return implode(', ', $return);
	}

	/**
	 * Splits the given size value(in bytes) into kilobytes, megabytes, gigabytes etc.
	 *
	 * @param $bytes
	 *
	 * @return mixed
	 */
	public function formatBytes($bytes) {
		$viewHelper = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\ViewHelpers\\Format\\BytesViewHelper');
		return $viewHelper->render($bytes);
	}
}