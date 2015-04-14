<?php
/**
 * Controller of the CacheCheck Module
 *
 * @author Julian Seitz <julian.seitz@hdnet.de
 */

namespace HDNET\CacheCheck\Controller;

use HDNET\Hdnet\Controller\AbstractController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
	 * initialize action
	 */
	protected function initializeAction() {
		parent::initializeAction();
		$this->cacheRegistry = GeneralUtility::makeInstance('HDNET\\CacheCheck\\Service\\CacheRegistry');
	}

	/**
	 * Assigns the given array to the view
	 */
	public function listAction() {
		$caches = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
		$caches = array_reverse($caches);

		$this->view->assign('caches', $caches);
	}

	/**
	 * action to start a cache analysis
	 *
	 * @param string $cacheName
	 */
	public function startAction($cacheName) {
		if (!in_array($cacheName, $this->cacheRegistry->getCurrent())) {
			$this->cacheRegistry->add($cacheName);
			$this->addFlashMessage('This cache "' . $cacheName . '" is now being analyzed');
		} else {
			$this->addFlashMessage('This cache "' . $cacheName . '" is already being analyzed', $messageTitle = '', $severity = \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
		}
		$this->redirect('list');
	}

	/**
	 * Action to stop a cache analysis
	 *
	 * @param string $cacheName
	 */
	public function stopAction($cacheName) {
		if (in_array($cacheName, $this->cacheRegistry->getCurrent())) {
			$this->cacheRegistry->remove($cacheName);
			$this->addFlashMessage('This cache "' . $cacheName . '" is not being analyzed anymore.');
		} else {
			$this->addFlashMessage('This cache "' . $cacheName . '" is not being analyzed', $messageTitle = '', $severity = \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
		}
		$this->redirect('list');
	}
}