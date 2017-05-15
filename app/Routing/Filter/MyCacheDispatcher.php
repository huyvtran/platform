<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.2
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('DispatcherFilter', 'Routing');

/**
 * This filter will check whether the response was previously cached in the file system
 * and served it back to the client if appropriate.
 *
 * @package Cake.Routing.Filter
 */
class MyCacheDispatcher extends DispatcherFilter {

/**
 * Default priority for all methods in this filter
 * This filter should run before the request gets parsed by router
 *
 * @var int
 */
	public $priority = 9;

/**
 * Checks whether the response was cached and set the body accordingly.
 *
 * @param CakeEvent $event containing the request and response object
 * @return CakeResponse with cached content if found, null otherwise
 */
	public function beforeDispatch(CakeEvent $event) {
		if (Configure::read('Cache.check') !== true) {
			return;
		}

		$isMobile = false;
        if (!empty($_SERVER['HTTP_MOBGAME_APPKEY'])) {
                $prefixview = $_SERVER['HTTP_MOBGAME_APPKEY'];
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
			
			$MobileDetect = new Mobile_Detect();

            $prefixview = $_SERVER['SERVER_NAME'];
            if ($MobileDetect->isMobile()) {
                $prefixview .= '_mobile';
                $isMobile = true;
            }
            if ($MobileDetect->isTablet()) {
            	$prefixview .= '_tablet';
            }

        }

        if (!empty($prefixview)) {
            Configure::write('Cache.viewPrefix', $prefixview);
        }

		$path = $event->data['request']->here();
		if ($path === '/') {
			$path = 'home';
		}

		$prefix = Configure::read('Cache.viewPrefix');
		if ($prefix) {
			$path = $prefix . '_' . $path;
		}
		$path = strtolower(Inflector::slug($path));

		$filename = CACHE . 'views' . DS . $path . '.php';

		if (!file_exists($filename)) {
			$filename = CACHE . 'views' . DS . $path . '_index.php';
		}
		if (file_exists($filename)) {

			# Delete cache if current file was detected fail mobile or pc version
			if (empty($_SERVER['HTTP_MOBGAME_APPKEY'])) {
				if ($isMobile && !empty($prefixview) && $prefixview == $_SERVER['SERVER_NAME']) {
					@unlink($filename);
					return;
				} elseif (!$isMobile && !empty($prefixview) && $prefixview == $_SERVER['SERVER_NAME'] . 'mobile_') {
					@unlink($filename);
					return;
				}
			}
			$controller = null;
			$view = new View($controller);
			$view->response = $event->data['response'];
			$result = $view->renderCache($filename, microtime(true));
			if ($result !== false) {
				$event->stopPropagation();
				$event->data['response']->body($result);
				return $event->data['response'];
			}
		}
	}

}
