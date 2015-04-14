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
}
