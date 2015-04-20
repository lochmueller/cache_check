<?php
/**
 * Controller of the CacheCheck Module
 *
 * @package CacheCheck\Controller
 * @author  Julian Seitz
 */

namespace HDNET\CacheCheck\Controller;

use HDNET\CacheCheck\Domain\Model\Cache;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class CacheCheckController
 */
class CacheCheckController extends ActionController {

	/**
	 * Cache registry
	 *
	 * @var \HDNET\CacheCheck\Service\CacheRegistry
	 * @inject
	 */
	protected $cacheRegistry;

	/**
	 * Cache repository
	 *
	 * @var \HDNET\CacheCheck\Domain\Repository\CacheRepository
	 * @inject
	 */
	protected $cacheRepository;

	/**
	 * Assigns the given array to the view
	 */
	public function listAction() {
		$this->view->assign('caches', $this->cacheRepository->findAll());
	}

	/**
	 * action to start a cache analysis
	 *
	 * @param \HDNET\CacheCheck\Domain\Model\Cache $cache
	 */
	public function startAction(Cache $cache) {
		if (!$cache->getIsInAnalyseMode()) {
			$this->cacheRegistry->add($cache->getName());
			$this->addFlashMessage('This cache "' . $cache->getName() . '" is now being analyzed');
		} else {
			$this->addFlashMessage('This cache "' . $cache->getName() . '" is already being analyzed', '', AbstractMessage::WARNING);
		}
		$this->redirect('list');
	}

	/**
	 * Action to stop a cache analysis
	 *
	 * @param \HDNET\CacheCheck\Domain\Model\Cache $cache
	 */
	public function stopAction(Cache $cache) {
		if ($cache->getIsInAnalyseMode()) {
			$this->cacheRegistry->remove($cache->getName());
			$this->addFlashMessage('This cache "' . $cache->getName() . '" is not being analyzed anymore.');
		} else {
			$this->addFlashMessage('This cache "' . $cache->getName() . '" is not being analyzed', '', AbstractMessage::WARNING);
		}
		$this->redirect('list');
	}

	/**
	 * Action to stop a cache analysis
	 *
	 * @param \HDNET\CacheCheck\Domain\Model\Cache $cache
	 */
	public function deleteAction(Cache $cache) {
		$this->getDatabaseConnection()
			->exec_DELETEquery('tx_cachecheck_domain_model_log', 'cache_name = "' . $cache->getName() . '"');
		$this->addFlashMessage('This cache "' . $cache->getName() . '" information is removed from log');
		$this->redirect('list');
	}

	/**
	 * Flush the given cache
	 *
	 * @param \HDNET\CacheCheck\Domain\Model\Cache $cache
	 */
	public function flushAction(Cache $cache) {
		/** @var CacheManager $cacheManager */
		$cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
		$cacheObject = $cacheManager->getCache($cache->getName());
		$cacheObject->flush();
		$this->addFlashMessage('The cache "' . $cache->getName() . '" was flushed');
		$this->redirect('list');
	}

	/**
	 * Get database connection
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}