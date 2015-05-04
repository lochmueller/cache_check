<?php
/**
 * Cache
 *
 * @package CacheCheck\Domain\Model
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Model;

use HDNET\CacheCheck\Service\KeyPerformanceIndicator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Cache
 *
 * @author Tim Lochmüller
 */
class Cache {

	/**
	 * Name of the cache
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Frontend
	 *
	 * @var string
	 */
	protected $frontend;

	/**
	 * Backend
	 *
	 * @var string
	 */
	protected $backend;

	/**
	 * Original backend. If set, than the cache is in analysed mode
	 *
	 * @var string
	 */
	protected $originalBackend;

	/**
	 * Groups
	 *
	 * @var array
	 */
	protected $groups;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * String representation
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get frontend
	 *
	 * @return string
	 */
	public function getFrontend() {
		return $this->frontend;
	}

	/**
	 * Set frontend
	 *
	 * @param string $frontend
	 */
	public function setFrontend($frontend) {
		$this->frontend = $frontend;
	}

	/**
	 * Get backend
	 *
	 * @return string
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * Set backend
	 *
	 * @param string $backend
	 */
	public function setBackend($backend) {
		$this->backend = $backend;
	}

	/**
	 * get original backend
	 *
	 * @return string
	 */
	public function getOriginalBackend() {
		return $this->originalBackend;
	}

	/**
	 * set original backend
	 *
	 * @param string $originalBackend
	 */
	public function setOriginalBackend($originalBackend) {
		$this->originalBackend = $originalBackend;
	}

	/**
	 * get groups
	 *
	 * @return array
	 */
	public function getGroups() {
		return $this->groups;
	}

	/**
	 * set groups
	 *
	 * @param array $groups
	 */
	public function setGroups($groups) {
		$this->groups = $groups;
	}

	/**
	 * get options
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * set options
	 *
	 * @param array $options
	 */
	public function setOptions($options) {
		$this->options = $options;
	}

	/**
	 * Check if the current cache is in analyse mode
	 *
	 * @return bool
	 */
	public function getIsInAnalyseMode() {
		return trim($this->getOriginalBackend()) !== '';
	}

	/**
	 * get static KPI
	 *
	 * @return array
	 */
	public function getStaticKpi() {
		return KeyPerformanceIndicator::getInstance()
			->getStatic($this);
	}

	/**
	 * get dynamic KPI and cache the output in a runtime cache to speed
	 * up the sorting of the caches in the SortService
	 *
	 * @return array
	 */
	public function getDynamicKpi() {
		static $dynamicCache = array();
		if ($dynamicCache[$this->getName()]) {
			return $dynamicCache[$this->getName()];
		}
		$dynamicCache[$this->getName()] = KeyPerformanceIndicator::getInstance()
			->getDynamic($this);
		return $dynamicCache[$this->getName()];
	}

	/**
	 * Get the real backend information
	 *
	 * @return string
	 */
	public function getRealBackend() {
		if (trim($this->getOriginalBackend()) !== '') {
			return $this->getOriginalBackend();
		}
		return $this->getBackend();
	}

	/**
	 * Checks if this cache is changeable
	 *
	 * @return bool
	 */
	public function getIsChangeable() {
		$cacheRegistry = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\CacheRegistry');
		return !in_array($this->getName(), $cacheRegistry->getNonChangeableCaches());
	}
}
