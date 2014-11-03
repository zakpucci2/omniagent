<?php

App::uses('AppController', 'Controller');

// App::uses('ConnectionManager', 'Model');

class CmsController extends AppController {

	public $name = 'Cms';
	public $helpers = array('Html', 'Form', 'Session', 'Fck', 'Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		Configure::write('debug', 2);
	}

	public function index() {
		$currentUserSession = $this->Session->read('User');
		if (empty($currentUserSession)) {
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$this->layout = 'internal';
		$this->set('title_for_layout', "Manage Content Pages");
		if ($this->request->isPost()) {
			$condition = array('CMS.title LIKE' => "{$this->request->data['User']['name']}%");
		} else {
			$condition = array();
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.User')
		);
		$res = $this->paginate('CMS');
		$this->set(compact('res'));
	}

	public function edit($id = null) {

		$currentUserSession = $this->Session->read('User');
		if (empty($currentUserSession)) {
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$id = base64_decode($id);
		$this->layout = 'internal';
		$this->set('title_for_layout', "Email Templates");
		$this->loadModel('EmailTemplate');
		if ($this->request->is('post')) {

			$this->EmailTemplate->set($this->request->data);

			if ($this->EmailTemplate->validates()) {
				$this->EmailTemplate->id = $id;
				if ($this->EmailTemplate->save($this->request->data['EmailTemplate'], false)) {
					$this->Session->setFlash('Email template has been updated successfully', 'success');
					$this->redirect(array('controller' => 'admins', 'action' => 'list_template'));
				}
			} else {
				//pr($this->User->validationErrors);echo 'error';
			}
		} else {
			$this->EmailTemplate->id = $id;
			$this->request->data = $this->EmailTemplate->read();
		}
	}

	public function list_department() {
		$currentUserSession = $this->Session->read('User');

		if (empty($currentUserSession)) {
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$usertypeID = $this->Session->read('UserTypeID');
		$this->set('user_session', $currentUserSession);
		$this->set('usertypeID', $usertypeID);
		$this->changeDb($usertypeID);
		$this->layout = 'internal';
		$this->set('title_for_layout', "List Departments");
		$this->loadModel('Department');
		$this->Department->unbindModel(array('belongsTo' => array('Checklist')));
		#added on 28th sept 2013
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$search = $_GET['search'];
			$this->paginate = array(
				'conditions' => array('OR' => array('Department.dept_shortname LIKE' => "$search%", 'Department.dept_name LIKE' => "$search%")),
				'limit' => Configure::read('LIST_NUM_RECORDS.User')
			);
		} else {
			$this->paginate = array(
				'conditions' => array(),
				'limit' => Configure::read('LIST_NUM_RECORDS.User')
			);
		}
		$res = $this->paginate('Department');
		$this->set(compact('res'));
	}

	public function edit_department($id = null) {

		$currentUserSession = $this->Session->read('User');
		if (empty($currentUserSession)) {
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}

		$id = base64_decode($id);
		$usertypeID = $this->Session->read('UserTypeID');
		$this->set('user_session', $currentUserSession);
		$this->set('usertypeID', $usertypeID);
		$this->changeDb($usertypeID);
		$this->layout = 'internal';
		$this->set('title_for_layout', "Edit Departments");
		$this->loadModel('Department');
		if ($this->request->is('post')) {

			$this->Department->set($this->request->data);

			if ($this->Department->validates()) {
				$this->Department->id = $id;
				if ($this->Department->save($this->request->data['Department'], false)) {
					$this->Session->setFlash('Department has been updated successfully', 'success');
					$this->redirect(array('controller' => 'admins', 'action' => 'list_department'));
				}
			} else {
				//pr($this->User->validationErrors);echo 'error';
			}
		} else {
			$this->Department->id = $id;
			$this->request->data = $this->Department->read();
		}
	}

	public function add_department() {
		$this->layout = 'internal';
		$this->set('title_for_layout', "Add Department");
		$this->loadModel('Department');
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($currentUserSession['User']['user_type_id'] == 3 || $currentUserSession['User']['user_type_id'] == 4) {
			$this->Session->setFlash('Sorry ! you are not authorised.', 'message');
			$this->redirect(array('controller' => 'users', 'action' => 'mydashboard'));
		}
		$usertypeID = $this->Session->read('UserTypeID');
		$this->set('user_session', $currentUserSession);
		$this->set('usertypeID', $usertypeID);
		$this->changeDb($usertypeID);
		if ($this->request->is('post')) {
			$this->Department->set($this->request->data);
			if ($this->Department->validates()) {
				$this->request->data['Department']['dept_guid'] = $this->generateGuid();
				if ($this->Department->save($this->request->data['Department'], false)) {
					$this->Session->setFlash('Department has been added successfully', 'success');
					$this->redirect(array('controller' => 'admins', 'action' => 'list_department'));
				}
			}
		}
	}

	public function admin_dashboard() {
		$this->layout = 'front';
		$this->set('title_for_layout', "User Signup | Login");
	}

}
