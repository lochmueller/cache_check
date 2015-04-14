<?php
/**
 * Cache
 *
 * @package Hdnet
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Model;

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
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
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
}
