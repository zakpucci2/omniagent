<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Pages';

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();
	public $components = array('Route');

	/**
	 * Displays a view
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index() {
		$this->_checkAdminUserSession();
		$this->layout = 'checklist';
		$this->set('title_for_layout', "Manage Content Pages");

		$this->request->data;
		if ($this->request->isPost()) {
			$condition = array('Page.title LIKE' => "{$this->request->data['User']['name']}%");
		} else {
			$condition = array();
		}

		$this->paginate = array(
			'conditions' => $condition,
			'limit' => 20,
			'order' => 'Page.sort_order'
		);
		$res = $this->paginate('Page');
		$this->set(compact('res'));
	}

	public function admin_add() {
		$this->_checkAdminUserSession();
		$path = func_get_args();
		if (count($path) > 0) {
			$PageID = (int) base64_Decode($path[0]);
			$PageData = $this->Page->findById($PageID);
			if ($PageData)
				$this->data = $PageData;
			else {
				$this->Session->setFlash('Page Content does not exists!', 'default', 'error');
				$this->redirect(array('controller' => 'pages', 'action' => 'index'));
			}
		}
		$this->layout = 'checklist';
		$this->set('title_for_layout', "Add/Edit Page");
		if ($this->request->is('post')) {
			if ($this->Page->save($this->data)) {
				if (empty($this->data['Page']['id']))
					$PageID = $this->Page->getLastInsertId();
				else {
					$PageData = $this->Page->findById($this->data['Page']['id']);
					$this->Route->remove($PageData['Page']['routepath']);
					$PageID = $this->data['Page']['id'];
				}
				$pageRoute = $this->CreateURL($this->data['Page']['title']);
				$route = "Router::connect('/" . $pageRoute . "', array('controller' => 'pages', 'action' => 'view', '" . $PageID . "'));";
				$this->Route->add($route);
				$RouteData['id'] = $PageID;
				$RouteData['routepath'] = $route;
				$RouteData['slug'] = $pageRoute;
				$this->Page->save($RouteData, array('validate', false));
				if (!empty($this->data['Page']['id']))
					$this->Session->setFlash('New Page added successfully', 'default', 'success');
				else
					$this->Session->setFlash('Page content has been updated successfully', 'default', 'success');
				$this->redirect(array('controller' => 'pages', 'action' => 'index'));
			}
		}
	}

	public function sortpages() {
		$this->autoRender = false;
		if ($this->request->data['mData']) {
			$sortOrder = explode(":", $this->request->data['mData']);
			$pageDetails = $this->Page->find('list', array('fields' => array('id', 'sort_order'), 'conditions' => array($sortOrder)));
			sort($pageDetails);
			$startOrder = $pageDetails[0];
			foreach ($sortOrder AS $key => $value) {
				$pageData['id'] = $value;
				$pageData['sort_order'] = $startOrder;
				$this->Page->saveAll($pageData);
				$startOrder++;
			}
		}
	}

	public function admin_delete() {
		$this->_checkAdminUserSession();
		$this->autoRender = false;
		$path = func_get_args();
		if (count($path) > 0) {
			$PageID = (int) base64_Decode($path[0]);
			$PageData = $this->Page->findById($PageID);
			if ($this->Page->delete($PageID)) {
				$this->Route->remove($PageData['Page']['routepath']);
				$this->Session->setFlash('Page deleted successfully!', 'default', 'success');
				$this->redirect(array('controller' => 'pages', 'action' => 'index'));
			}
		}
	}

	public function admin_status() {
		$this->_checkAdminUserSession();
		$this->autoRender = false;
		$path = func_get_args();
		if (count($path) > 0) {
			$PageID = (int) base64_Decode($path[0]);
			$PageData = $this->Page->findById($PageID);
			if ($PageData) {
				if ($PageData['Page']['view_status'] == 0)
					$PageData['Page']['view_status'] = 1;
				else
					$PageData['Page']['view_status'] = 0;
				$this->Page->save($PageData);
				$this->Session->setFlash('Page status has been updated successfully!', 'default', 'success');
				$this->redirect(array('controller' => 'pages', 'action' => 'index'));
			}
		}
	}

	public function view() {
		$this->layout = 'checklist';
		$path = func_get_args();
		if (count($path) > 0) {
			$PageID = $path[0];
			$PageData = $this->Page->findById($PageID);
			$this->set('title_for_layout', $PageData['Page']['title']);
			$this->set("PageData", $PageData);
		} else
			$this->redirect('/');
	}

	private function CreateURL($URL) {
		if ($URL) {
			return strtolower(preg_replace("/\W+/", "-", $URL));
		} else {
			return false;
		}
	}

	public function admin_ajax_seach_pages() {
		$this->loadModel('Page');
		$this->autoRender = false;
		$conditions = isset($this->params->query['q']) ? array('Page.title LIKE' => $this->params->query['q'] . '%') : array();
		$data = $this->Page->find('list', array(
			'fields' => array('Page.id', 'Page.title'),
			'conditions' => $conditions,
			'recursive' => -1
		));
		echo json_encode($data);
	}

}
