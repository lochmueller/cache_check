<?php
/**
 * Controller of the CacheCheck Module
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Controller;

use HDNET\CacheCheck\Domain\Model\Cache;
use HDNET\Hdnet\Controller\AbstractController;
use TYPO3\CMS\Core\Messaging\AbstractMessage;

/**
 * Class CacheCheckController
 *
 * @package HDNET\CacheCheck\Controller
 */
class CacheCheckController extends AbstractController {

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

		// @todo delete

		$this->addFlashMessage('This cache "' . $cache->getName() . '" information are removed from log');
		$this->redirect('list');
	}
}