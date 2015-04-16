<?php
/**
 * Log entry
 *
 * @package CacheCheck\Domain\Model
 * @author  Tim Lochmüller
 */

namespace HDNET\CacheCheck\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Log entry
 *
 * @author       Tim Lochmüller
 * @db
 * @smartExclude Language,Workspaces
 */
class Log extends AbstractEntity {

	/**
	 * Time stamp
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $timestamp;

	/**
	 * Request hash
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $requestHash;

	/**
	 * cache name
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $cacheName;

	/**
	 * called method
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $calledMethod;

	/**
	 * entry identifier
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $entryIdentifier;

	/**
	 * Entry size
	 *
	 * @var string
	 * @db varchar(255) DEFAULT '' NOT NULL
	 */
	protected $entrySize;

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @param string $timestamp
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @return string
	 */
	public function getRequestHash() {
		return $this->requestHash;
	}

	/**
	 * @param string $requestHash
	 */
	public function setRequestHash($requestHash) {
		$this->requestHash = $requestHash;
	}

	/**
	 * @return string
	 */
	public function getCacheName() {
		return $this->cacheName;
	}

	/**
	 * @param string $cacheName
	 */
	public function setCacheName($cacheName) {
		$this->cacheName = $cacheName;
	}

	/**
	 * @return string
	 */
	public function getCalledMethod() {
		return $this->calledMethod;
	}

	/**
	 * @param string $calledMethod
	 */
	public function setCalledMethod($calledMethod) {
		$this->calledMethod = $calledMethod;
	}

	/**
	 * @return string
	 */
	public function getEntryIdentifier() {
		return $this->entryIdentifier;
	}

	/**
	 * @param string $entryIdentifier
	 */
	public function setEntryIdentifier($entryIdentifier) {
		$this->entryIdentifier = $entryIdentifier;
	}

	/**
	 * @return string
	 */
	public function getEntrySize() {
		return $this->entrySize;
	}

	/**
	 * @param string $entrySize
	 */
	public function setEntrySize($entrySize) {
		$this->entrySize = $entrySize;
	}
}
