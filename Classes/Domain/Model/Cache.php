<?php
/**
 * Cache
 *
 * @package CacheCheck\Domain\Model
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Model;

use HDNET\CacheCheck\Service\KeyPerformanceIndicator;

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
	 * @return string
	 */
	public function getFrontend() {
		return $this->frontend;
	}

	/**
	 * @param string $frontend
	 */
	public function setFrontend($frontend) {
		$this->frontend = $frontend;
	}

	/**
	 * @return string
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * @param string $backend
	 */
	public function setBackend($backend) {
		$this->backend = $backend;
	}

	/**
	 * @return string
	 */
	public function getOriginalBackend() {
		return $this->originalBackend;
	}

	/**
	 * @param string $originalBackend
	 */
	public function setOriginalBackend($originalBackend) {
		$this->originalBackend = $originalBackend;
	}

	/**
	 * @return array
	 */
	public function getGroups() {
		return $this->groups;
	}

	/**
	 * @param array $groups
	 */
	public function setGroups($groups) {
		$this->groups = $groups;
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
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
	 * get dynamic KPI
	 *
	 * @return array
	 */
	public function getDynamicKpi() {
		return KeyPerformanceIndicator::getInstance()
			->getDynamic($this);
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
}
