<?php
/**
 * Class description
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Service;


use TYPO3\CMS\Core\Resource\Utility\BackendUtility;

class FormatService extends AbstractService {

	/**
	 * @var array
	 */
	protected $time = array();

	/**
	 * Manages convert functions for given time value
	 *
	 * @param        $seconds
	 * @param string $labels
	 *
	 * @return string
	 */
	public function timeConvert($seconds, $labels = 'seconds|minutes|hours|days|years') {
		$labelArr = explode('|', $labels);
		$absSeconds = abs($seconds);
		$this->time = array();
		$this->minutesConvert($absSeconds);
		$newTime = '';
		if (array_key_exists($labelArr[4], $this->time)) {
			$newTime .= $this->time[$labelArr[4]] . ' ' . $labelArr[4] . ', ';
		}
		if (array_key_exists($labelArr[3], $this->time)) {
			$newTime .= $this->time[$labelArr[3]] . ' ' . $labelArr[3] . ', ';
		}
		if (array_key_exists($labelArr[2], $this->time)) {
			$newTime .= $this->time[$labelArr[2]] . ' ' . $labelArr[2] . ', ';
		}
		if (array_key_exists($labelArr[1], $this->time)) {
			$newTime .= $this->time[$labelArr[1]] . ' ' . $labelArr[1] . ', ';
		}
		$newTime .= $this->time[$labelArr[0]] . ' ' . $labelArr[0] . ', ';
		return $newTime;
	}

	/**
	 * Converts given time to minutes, calls functions for further calculation
	 *
	 * @param $time
	 *
	 * @return array|null
	 */
	public function minutesConvert($time) {
		if ($time > 3600) {
			$this->hoursConvert($time);
			return NULL;
		}
		$val = $time / 60;
		if (is_int($val)) {
			return array('minutes' => $val);
		}
		$calc = round($val);
		$calc = $calc * 60;
		$leftOver = $time - $calc;
		$this->time['minutes'] = $val;
		$this->time['seconds'] = $leftOver;
		return NULL;
	}

	/**
	 * Converts given time to hours, calls functions for further calculation
	 *
	 * @param $time
	 *
	 * @return null
	 */
	public function hoursConvert($time) {
		if ($time > 24 * 3600) {
			$this->daysConvert($time);
			return NULL;
		}
		$val = $time / 3600;
		if (is_int($val)) {
			$this->time['hours'] = $val;
			return NULL;
		}
		$calc = round($val);
		$calc = $calc * 3600;
		$leftOver = $time - $calc;
		$this->time['hours'] = $val;
		$this->minutesConvert($leftOver);
		return NULL;
	}

	/**
	 * Converts given time to days, calls functions for further calculation
	 *
	 * @param $time
	 *
	 * @return null
	 */
	public function daysConvert($time) {
		if ($time > 365 * 24 * 3600) {
			$this->yearsConvert($time);
			return NULL;
		}
		$val = $time / (24 * 3600);
		if (is_int($val)) {
			$this->time['days'] = $val;
			return NULL;
		}
		$calc = round($val);
		$calc = $calc * (24 * 3600);
		$leftOver = $time - $calc;
		$this->time['day'] = $val;
		$this->minutesConvert($leftOver);
		return NULL;
	}

	/**
	 * Converts given time to years, calls functions for further calculation
	 *
	 * @param $time
	 *
	 * @return null
	 */
	public function yearsConvert($time) {
		$val = $time / (365 * 24 * 3600);
		if (is_int($val)) {
			$this->time['years'] = $val;
			return NULL;
		}
		$calc = round($val);
		$calc = $calc * (365 * 24 * 3600);
		$leftOver = $time - $calc;
		$this->time['years'] = $val;
		$this->minutesConvert($leftOver);
		return NULL;
	}
}
// \TYPO3\CMS\Fluid\ViewHelpers\Format\ByteViewHelper::render();