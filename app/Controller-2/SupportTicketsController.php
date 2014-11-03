<?php

/*
 * ***********************************************************************************
 * SupportTickets Controller
 * Functionality		 :	SupportTickets related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class SupportTicketsController extends AppController {

	public $name = 'SupportTickets';
	public $uses = array('SupportTicket', 'User');
	public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common', 'Time');
	public $components = array('Email', 'RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function listtickets() {
		$this->layout = 'user_layout';
		$this->set('title_for_layout', "Support Tickets");
		$condition = array(
			'SupportTicket.user_id' => $this->Session->read('User.User.id'),
			'SupportTicket.is_user_deleted <> ' => 1
		);
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.User'),
			'order' => 'SupportTicket.id DESC'
		);
		$data = $this->paginate('SupportTicket');
		$this->set(compact('data'));
	}

	public function admin_listtickets() {
		$this->layout = 'admin_layout';
		$this->set('title_for_layout', "Support Tickets");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'SupportTicket.admin_id' => $this->Session->read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status <> ' => 5,
				'OR' => array(
					'SupportTicket.subject LIKE' => "{$_GET['search']}%",
					'SupportTicket.message LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'SupportTicket.admin_id' => $this->Session->read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status <> ' => 5
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
			'order' => 'SupportTicket.id DESC'
		);
		$data = $this->paginate('SupportTicket');
		$this->set(compact('data'));
	}

	public function admin_closedtickets() {
		$this->layout = 'admin_layout';
		$this->set('title_for_layout', "Closed Support Tickets");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'SupportTicket.admin_id' => $this->Session->read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status' => 5,
				'OR' => array(
					'SupportTicket.subject LIKE' => "{$_GET['search']}%",
					'SupportTicket.message LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'SupportTicket.admin_id' => $this->Session->read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status' => 5
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
			'order' => 'SupportTicket.id DESC'
		);
		$data = $this->paginate('SupportTicket');
		$this->set(compact('data'));
	}
	
	public function superadmin_listtickets() {
		$this->layout = 'superadmin_layout';
		$this->set('title_for_layout', "Support Tickets");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status <> ' => 5,
				'OR' => array(
					'SupportTicket.subject LIKE' => "{$_GET['search']}%",
					'SupportTicket.message LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status <> ' => 5,
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
			'order' => 'SupportTicket.id DESC'
		);
		$data = $this->paginate('SupportTicket');
		$this->set(compact('data'));
		$this->loadModel('User');
		$user_res = $this->User->find(
			'list', array(
				'conditions' => array(
					'User.user_type_id' => Configure::read('UserType.admin'), 
					'User.is_deleted' => 0, 
					'User.is_blocked' => 0
				), 
				'fields' => array(
					'User.id', 'User.full_name'
				), 
				'order' => array(
					'User.first_name ASC'
				), 
				'recursive' => -1
			)
		);
		$this->set('user_res', $user_res);
		$str = '';
		foreach ($user_res as $key => $val):
			$str.="{value: $key, text: '$val'},";
		endforeach;
		$this->set('users', trim($str));
	}

	public function superadmin_closedtickets() {
		$this->layout = 'superadmin_layout';
		$this->set('title_for_layout', "Closed Support Tickets");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status' => 5,
				'OR' => array(
					'SupportTicket.subject LIKE' => "{$_GET['search']}%",
					'SupportTicket.message LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status' => 5,
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
			'order' => 'SupportTicket.id DESC'
		);
		$data = $this->paginate('SupportTicket');
		$this->set(compact('data'));
	}
	
	public function assignAdmin() {
		$this->autoRender = false;
		$currentUserSession = $this->Session->read('User');
		$this->layout = 'ajax';
		if (!empty($this->request->data['value'])) {
			$this->SupportTicket->id = $this->request->data['pk'];
			$data['SupportTicket']['admin_id'] = $this->request->data['value'];
			$data['SupportTicket']['status'] = 2;
			$this->SupportTicket->save($data['SupportTicket']);
		}
	}
	
	public function add_support_ticket() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->SupportTicket->set($this->request->data);
			if ($this->SupportTicket->validates()) {
				$user = $this->Session->read('User');
				if(!empty($user['AssociatedAdmin']) && is_numeric($user['AssociatedAdmin']['id'])) {
					$this->request->data['SupportTicket']['admin_id'] = $user['AssociatedAdmin']['id'];
				}
				$this->request->data['SupportTicket']['user_id'] = $this->Session->read('User.User.id');
				if ($this->SupportTicket->save($this->request->data)) {
					$this->Session->setFlash('Support ticket has been sent successfully', 'default', 'success');
				} else {
					$this->Session->setFlash('Error found. Support ticket not sent successfully.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->SupportTicket->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send Support ticket. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets'));
	}
	
	public function admin_add_support_ticket() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->SupportTicket->set($this->request->data);
			if ($this->SupportTicket->validates()) {
				$this->request->data['SupportTicket']['user_id'] = $this->Session->read('User.User.id');
				if ($this->SupportTicket->save($this->request->data)) {
					$this->Session->setFlash('Support ticket has been sent successfully', 'default', 'success');
				} else {
					$this->Session->setFlash('Error found. Support ticket not sent successfully.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->SupportTicket->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send Support ticket. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
	}

	public function delete_ticket($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->SupportTicket->id = $id;
		if ($this->SupportTicket->exists()) {
			$this->SupportTicket->id = $id;
			$this->SupportTicket->saveField('is_user_deleted', 1);
			$this->Session->setFlash('Support ticket has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Support ticket is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets'));
	}

	public function delete_alltickets() {
		$this->autoRender = false;
		$tickets = $this->SupportTicket->find('all', array(
			'fields' => array('SupportTicket.id'),
			'conditions' => array(
				'SupportTicket.user_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('SupportTicket.id ASC')
		));
		foreach ($tickets as $key => $ticket) {
			$this->SupportTicket->id = (int)$ticket['SupportTicket']['id'];
			$this->SupportTicket->saveField('is_user_deleted', 1);
		}
		$this->Session->setFlash('All Support ticket(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets'));
	}
	
	public function admin_delete_ticket($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->SupportTicket->id = $id;
		if ($this->SupportTicket->exists()) {
			$this->SupportTicket->id = $id;
			$this->SupportTicket->saveField('is_admin_deleted', 1);
			$this->Session->setFlash('Support ticket has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Support ticket is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'admin' => true));
	}

	public function superadmin_delete_ticket($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->SupportTicket->id = $id;
		if ($this->SupportTicket->exists()) {
			$this->SupportTicket->id = $id;
			$this->SupportTicket->saveField('is_superadmin_deleted', 1);
			$this->Session->setFlash('Support ticket has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Support ticket is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'superadmin' => true));
	}

	public function admin_delete_alltickets() {
		$this->autoRender = false;
		$tickets = $this->SupportTicket->find('all', array(
			'fields' => array('SupportTicket.id'),
			'conditions' => array(
				'SupportTicket.admin_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('SupportTicket.id ASC')
		));
		foreach ($tickets as $key => $ticket) {
			$this->SupportTicket->id = (int)$ticket['SupportTicket']['id'];
			$this->SupportTicket->saveField('is_admin_deleted', 1);
		}
		$this->Session->setFlash('All Support ticket(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'admin' => true));
	}

	public function superadmin_delete_alltickets() {
		$this->autoRender = false;
		$tickets = $this->SupportTicket->find('all', array(
			'fields' => array('SupportTicket.id'),
			'order' => array('SupportTicket.id ASC')
		));
		foreach ($tickets as $key => $ticket) {
			$this->SupportTicket->id = (int) $ticket['SupportTicket']['id'];
			$this->SupportTicket->saveField('is_superadmin_deleted', 1);
		}
		$this->Session->setFlash('All Support ticket(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'superadmin' => true));
	}

	public function view_ticket($id = null) {
		//$this->autoRender = false;
		$PopupTitle = "Support Ticket Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$ticket = $this->SupportTicket->find('first', array(
				'conditions' => array(
					'SupportTicket.id' => $id
				)
			));
			$this->set('ticketData', $ticket);
			$this->set('_serialize', array('ticketData', 'PopupTitle'));
		}
	}

	public function admin_view_ticket($id = null) {
		//$this->autoRender = false;
		$PopupTitle = "Support Ticket Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$ticket = $this->SupportTicket->find('first', array(
				'conditions' => array(
					'SupportTicket.id' => $id
				)
			));
			$this->set('ticketData', $ticket);
			$this->set('_serialize', array('ticketData', 'PopupTitle'));
		}
	}
	
	public function superadmin_view_ticket($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Support Ticket Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$ticket = $this->SupportTicket->find('first', array(
				'conditions' => array(
					'SupportTicket.id' => $id
				)
			));
			$this->set('ticketData', $ticket);
			$this->set('_serialize', array('ticketData', 'PopupTitle'));
		}
	}
	
	public function superadmin_reply_ticket($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Reply to Sender";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$ticket = $this->SupportTicket->find('first', array(
				'conditions' => array(
					'SupportTicket.id' => $id
				)
			));
			$this->set('ticketData', $ticket);
			$this->set('_serialize', array('ticketData', 'PopupTitle'));
		}
	}
	
	public function admin_reply_ticket($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Reply to Sender";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$ticket = $this->SupportTicket->find('first', array(
				'conditions' => array(
					'SupportTicket.id' => $id
				)
			));
			$this->set('ticketData', $ticket);
			$this->set('_serialize', array('ticketData', 'PopupTitle'));
		}
	}
	
	public function admin_mark_fixed($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->SupportTicket->id = $id;
		if ($this->SupportTicket->exists()) {
			$this->SupportTicket->id = $id;
			$this->SupportTicket->saveField('status', 4);
			// $this->SupportTicket->saveField('closed_date', date(Configure::read('DB_SAVE_DATETIME_FORMAT.DateTime'), time()));
			$this->Session->setFlash('Support ticket has been updated successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Due to some technical issue support ticket is not updated', 'default', 'error');
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'admin' => true));
	}
	
	public function superadmin_mark_complete($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->SupportTicket->id = $id;
		if ($this->SupportTicket->exists()) {
			$this->SupportTicket->id = $id;
			$this->SupportTicket->saveField('status', 5);
			$this->SupportTicket->saveField('closed_date', date(Configure::read('DB_SAVE_DATETIME_FORMAT.DateTime'), time()));
			$this->Session->setFlash('Support ticket has been closed successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Due to some technical issue support ticket is not closed', 'default', 'error');
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'superadmin' => true));
	}

	public function latestSupportTickets() {
		$timeStart = time();
		$this->layout = 'ajax';
		$this->autoRender = false;
		$userId = $this->Session->read('User.User.id');
		if ($this->request->is('post')) {
			if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
				$timestamp = $this->request->data['timestamp'];
			} else {
				// get current database time
				$nowTime = $this->SupportTicket->getMySQLNowTimestamp();
				$timestamp = $nowTime[0][0]['timestamp'];
			}
		} else {
			$nowTime2 = $this->SupportTicket->getMySQLNowTimestamp();
			$timestamp = $nowTime2[0][0]['timestamp'];
		}
		$newData = false;
		$tickets = array();

		// loop while there is no new data and is running for less than 20 seconds
		while (!$newData && (time() - $timeStart) < 30) {
			// check for new data
			$ticketsCount = $this->SupportTicket->getNewSupportTicketsCount();
			if (isset($ticketsCount)) {
				$tickets[] = $ticketsCount;
				$newData = true;
			}
			// let the server rest for a while
			usleep(1000000);
		}

		// get current database time
		$nowTime3 = $this->SupportTicket->getMySQLNowTimestamp();
		$timestamp = $nowTime3[0][0]['timestamp'];

		// output
		$data = array('tickets' => $tickets, 'timestamp' => $timestamp);
		echo json_encode($data);
		exit;
	}

}
