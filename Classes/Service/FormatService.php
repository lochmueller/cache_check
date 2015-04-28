<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Service;


use TYPO3\CMS\Core\Resource\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class FormatService extends AbstractService {

	const SECOND = 1;

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
}

// \TYPO3\CMS\Fluid\ViewHelpers\Format\BytesViewHelper::render();

#\TYPO3\CMS\Backend\Utility\BackendUtility::calcAge();