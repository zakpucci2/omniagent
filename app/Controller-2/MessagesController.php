<?php

/*
 * ***********************************************************************************
 * Messages Controller
 * Functionality		 :	Messages related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class MessagesController extends AppController {

	public $name = 'Messages';
	public $uses = array('Message', 'UserMessage');
	public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common', 'Time');
	public $components = array('Email', 'RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function listmessages() {
		$this->layout = 'user_layout';
		$this->set('title_for_layout', "Messages");
		$condition = array(
			'UserMessage.receiver_id' => $this->Session->read('User.User.id'),
			'UserMessage.is_receiver_deleted <> ' => 1
		);
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.User'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function sentmessages() {
		$this->layout = 'user_layout';
		$this->set('title_for_layout', "Sent Messages");
		$condition = array(
			'UserMessage.sender_id' => $this->Session->read('User.User.id'),
			'UserMessage.is_sender_deleted <> ' => 1
		);
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.User'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function admin_listmessages() {
		$this->layout = 'admin_layout';
		$this->set('title_for_layout', "Messages");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'UserMessage.receiver_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_receiver_deleted <> ' => 1,
				'OR' => array(
					'Message.subject LIKE' => "{$_GET['search']}%",
					'Message.body LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'UserMessage.receiver_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_receiver_deleted <> ' => 1
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function admin_sentmessages() {
		$this->layout = 'admin_layout';
		$this->set('title_for_layout', "Sent Messages");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'UserMessage.sender_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_sender_deleted <> ' => 1,
				'OR' => array(
					'Message.subject LIKE' => "{$_GET['search']}%",
					'Message.body LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'UserMessage.sender_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_sender_deleted <> ' => 1
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function superadmin_listmessages() {
		$this->layout = 'superadmin_layout';
		$this->set('title_for_layout', "Messages");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'UserMessage.receiver_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_receiver_deleted <> ' => 1,
				'OR' => array(
					'Message.subject LIKE' => "{$_GET['search']}%",
					'Message.body LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'UserMessage.receiver_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_receiver_deleted <> ' => 1
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function superadmin_sentmessages() {
		$this->layout = 'superadmin_layout';
		$this->set('title_for_layout', "Sent Messages");
		$currentUserSession = $this->Session->read('User');
		$id = $currentUserSession['User']['id'];
		if ($this->request->isGet() && isset($_GET['search'])) {
			$condition = array(
				'UserMessage.sender_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_sender_deleted <> ' => 1,
				'OR' => array(
					'Message.subject LIKE' => "{$_GET['search']}%",
					'Message.body LIKE' => "{$_GET['search']}%"
				)
			);
		} else {
			$condition = array(
				'UserMessage.sender_id' => $this->Session->read('User.User.id'),
				'UserMessage.is_sender_deleted <> ' => 1
			);
		}
		$this->paginate = array(
			'conditions' => $condition,
			'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
			'order' => 'UserMessage.id DESC'
		);
		$data = $this->paginate('UserMessage');
		$this->set(compact('data'));
	}

	public function compose_message() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['parent_message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages'));
	}

	public function admin_compose_message() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['parent_message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true));
	}

	public function superadmin_compose_message() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['parent_message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'superadmin' => true));
	}

	public function reply_message($id = null) {
		$PopupTitle = "Reply Message";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $savedMessage['Message']['id'];
						$this->request->data['UserMessage']['parent_message_id'] = $this->request->data['UserMessage']['parent_message_id'];
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
			$this->redirect(array('controller' => 'messages', 'action' => 'listmessages'));
		} else {
			if ($this->RequestHandler->isAjax()) {
				$message = $this->UserMessage->find('first', array(
					'conditions' => array(
						'UserMessage.message_id' => $id
					)
				));
				$this->set('message', $message);
				$this->set('_serialize', array('message', 'PopupTitle'));
			}
		}
	}

	public function admin_reply_message($id = null) {
		$PopupTitle = "Reply Message";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $savedMessage['Message']['id'];
						$this->request->data['UserMessage']['parent_message_id'] = $this->request->data['UserMessage']['parent_message_id'];
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
			$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true));
		} else {
			if ($this->RequestHandler->isAjax()) {
				$message = $this->UserMessage->find('first', array(
					'conditions' => array(
						'UserMessage.message_id' => $id
					)
				));
				$this->set('message', $message);
				$this->set('_serialize', array('message', 'PopupTitle'));
			}
		}
	}

	public function superadmin_reply_message($id = null) {
		$PopupTitle = "Reply Message";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $savedMessage['Message']['id'];
						$this->request->data['UserMessage']['parent_message_id'] = $this->request->data['UserMessage']['parent_message_id'];
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
			$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'superadmin' => true));
		} else {
			if ($this->RequestHandler->isAjax()) {
				$message = $this->UserMessage->find('first', array(
					'conditions' => array(
						'UserMessage.message_id' => $id
					)
				));
				$this->set('message', $message);
				$this->set('_serialize', array('message', 'PopupTitle'));
			}
		}
	}

	public function delete_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_receiver_deleted', 1);
			$this->Session->setFlash('Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages'));
	}

	public function delete_sent_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_sender_deleted', 1);
			$this->Session->setFlash('Sent Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Sent Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages'));
	}

	public function admin_delete_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_receiver_deleted', 1);
			$this->Session->setFlash('Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true));
	}

	public function admin_delete_sent_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_sender_deleted', 1);
			$this->Session->setFlash('Sent Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Sent Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages', 'admin' => true));
	}

	public function superadmin_delete_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_receiver_deleted', 1);
			$this->Session->setFlash('Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'superadmin' => true));
	}

	public function superadmin_delete_sent_message($id = null) {
		$this->autoRender = false;
		$id = base64_decode($id);
		$this->UserMessage->id = $id;
		if ($this->UserMessage->exists()) {
			$this->UserMessage->id = $id;
			$this->UserMessage->saveField('is_sender_deleted', 1);
			$this->Session->setFlash('Sent Message has been deleted successfully', 'default', 'success');
		} else {
			$this->Session->setFlash('Sent Message is not deleted', 'default', 'error');
		}
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages', 'superadmin' => true));
	}

	public function delete_allmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.receiver_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_receiver_deleted', 1);
		}
		$this->Session->setFlash('All Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages'));
	}

	public function delete_allsentmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.sender_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_sender_deleted', 1);
		}
		$this->Session->setFlash('All Sent Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages'));
	}

	public function admin_delete_allmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.receiver_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_receiver_deleted', 1);
		}
		$this->Session->setFlash('All Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true));
	}

	public function admin_delete_allsentmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.sender_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_sender_deleted', 1);
		}
		$this->Session->setFlash('All Sent Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages', 'admin' => true));
	}

	public function superadmin_delete_allmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.receiver_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_receiver_deleted', 1);
		}
		$this->Session->setFlash('All Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'listmessages', 'superadmin' => true));
	}

	public function superadmin_delete_allsentmessage() {
		$this->autoRender = false;
		$messages = $this->UserMessage->find('all', array(
			'fields' => array('UserMessage.id'),
			'conditions' => array(
				'UserMessage.sender_id =' => $this->Session->read('User.User.id'),
			),
			'order' => array('UserMessage.id ASC')
		));
		foreach ($messages as $key => $message) {
			$this->UserMessage->id = (int) $message['UserMessage']['id'];
			$this->UserMessage->saveField('is_sender_deleted', 1);
		}
		$this->Session->setFlash('All sent Message(s) has been deleted successfully', 'default', 'success');
		$this->redirect(array('controller' => 'messages', 'action' => 'sentmessages', 'superadmin' => true));
	}

	public function view_message($id = null) {
		//$this->autoRender = false;
		$PopupTitle = "Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.receiver_id' => $this->Session->read('User.User.id')
				)
			));
			if (!empty($message) && $message['UserMessage']['is_read'] != 1) {
				$this->UserMessage->id = (int)$message['UserMessage']['id'];
				$this->UserMessage->saveField('is_read', 1);
				$this->UserMessage->saveField('read_datetime', date('Y-m-d H:i:s'));
			}
			$this->set('messageData', $message);
			$this->set('_serialize', array('messageData', 'PopupTitle'));
		}
	}

	public function view_sent_message($id = null) {
		//$this->autoRender = false;
		$PopupTitle = "Sent Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.sender_id' => $this->Session->read('User.User.id')
				)
			));
			$this->set('message', $message);
			$this->set('_serialize', array('message', 'PopupTitle'));
		}
	}

	public function admin_view_message($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.receiver_id' => $this->Session->read('User.User.id')
				)
			));
			$this->UserMessage->id = (int)$message['UserMessage']['id'];
			$this->UserMessage->saveField('is_read', 1);
			$this->UserMessage->saveField('read_datetime', date('Y-m-d H:i:s'));
			$this->set('message', $message);
			$this->set('_serialize', array('message', 'PopupTitle'));
		}
	}

	public function admin_view_sent_message($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.sender_id' => $this->Session->read('User.User.id')
				)
			));
			$this->UserMessage->id = (int)$message['UserMessage']['id'];
			$this->set('message', $message);
			$this->set('_serialize', array('message', 'PopupTitle'));
		}
	}

	public function superadmin_view_message($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.receiver_id' => $this->Session->read('User.User.id')
				)
			));
			$this->UserMessage->id = (int)$message['UserMessage']['id'];
			$this->UserMessage->saveField('is_read', 1);
			$this->UserMessage->saveField('read_datetime', date('Y-m-d H:i:s'));
			$this->set('message', $message);
			$this->set('_serialize', array('message', 'PopupTitle'));
		}
	}

	public function superadmin_view_sent_message($id = null) {
		// $this->autoRender = false;
		$PopupTitle = "Message Details";
		$id = base64_decode($id);
		$this->set("PopupTitle", $PopupTitle);
		if ($this->RequestHandler->isAjax()) {
			$message = $this->UserMessage->find('first', array(
				'conditions' => array(
					'UserMessage.message_id' => $id,
					'UserMessage.sender_id' => $this->Session->read('User.User.id')
				)
			));
			$this->UserMessage->id = (int)$message['UserMessage']['id'];
			$this->set('message', $message);
			$this->set('_serialize', array('message', 'PopupTitle'));
		}
	}

	public function latestMessages() {
		$timeStart = time();
		$this->layout = 'ajax';
		$this->autoRender = false;
		$userId = $this->Session->read('User.User.id');
		if ($this->request->is('post')) {
			if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
				$timestamp = $this->request->data['timestamp'];
			} else {
				// get current database time
				$nowTime = $this->Message->getMySQLNowTimestamp();
				$timestamp = $nowTime[0][0]['timestamp'];
			}
		} else {
			$nowTime2 = $this->Message->getMySQLNowTimestamp();
			$timestamp = $nowTime2[0][0]['timestamp'];
		}
		$newData = false;
		$messages = array();

		// loop while there is no new data and is running for less than 20 seconds
		while (!$newData && (time() - $timeStart) < 30) {
			// check for new data
			$messagesCount = $this->UserMessage->getNewMessagesCount();
			if (isset($messagesCount)) {
				$messages[] = $messagesCount;
				$newData = true;
			}
			// let the server rest for a while
			usleep(1000000);
		}

		// get current database time
		$nowTime3 = $this->Message->getMySQLNowTimestamp();
		$timestamp = $nowTime3[0][0]['timestamp'];

		// output
		$data = array('messages' => $messages, 'timestamp' => $timestamp);
		echo json_encode($data);
		exit;
	}

	public function latestBoxMessages() {
		$timeStart = time();
		$this->layout = 'ajax';
		$this->autoRender = false;
		$userId = $this->Session->read('User.User.id');
		if ($this->request->is('post')) {
			if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
				$timestamp = $this->request->data['timestamp'];
			} else {
				// get current database time
				$nowTime = $this->Message->getMySQLNowTimestamp();
				$timestamp = $nowTime[0][0]['timestamp'];
			}
		} else {
			$nowTime2 = $this->Message->getMySQLNowTimestamp();
			$timestamp = $nowTime2[0][0]['timestamp'];
		}
		$newData = false;
		$messages = array();

		// loop while there is no new data and is running for less than 20 seconds
		while (!$newData && (time() - $timeStart) < 30) {
			// check for new data
			$messagesList = $this->UserMessage->getNewRecievedMessages($timeStart);
			if (!empty($messagesList)) {
				$messages = $messagesList;
				$newData = true;
			}
			// let the server rest for a while
			usleep(1000000);
		}

		// get current database time
		$nowTime3 = $this->Message->getMySQLNowTimestamp();
		$timestamp = $nowTime3[0][0]['timestamp'];

		// output
		$data = array('messages' => $messages, 'timestamp' => $timestamp);
		echo json_encode($data);
		exit;
	}	
	
	public function superadmin_reply_ticket() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['parent_message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'superadmin' => true));
	}

	public function admin_reply_ticket() {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Message->set($this->request->data);
			if ($this->Message->validates()) {
				$this->loadModel('User');
				$userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Message']['receiver_user_name']);
				$userData = $this->User->findByUserName($userName);
				if (!empty($userData)) {
					$savedMessage = $this->Message->save($this->request->data);
					if (!empty($savedMessage)) {
						$this->request->data['UserMessage']['message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['parent_message_id'] = $this->Message->id;
						$this->request->data['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
						$this->request->data['UserMessage']['receiver_id'] = $userData['User']['id'];
						if ($this->Message->UserMessage->save($this->request->data['UserMessage'])) {
							$this->Session->setFlash('Message has been sent successfully', 'default', 'success');
						} else {
							$this->Session->setFlash('Error found. Message not sent successfully.', 'default', 'error');
						}
					}
				} else {
					$this->Session->setFlash('User does not exist with this user name.', 'default', 'error');
				}
			} else {
				$str = '';
				foreach ($this->Message->validationErrors as $error) {
					$str .= $error[0] . '<br/>';
				}
				$this->Session->setFlash('Unable to send message. <br/>' . $str, 'default', 'error');
			}
		}
		$this->redirect(array('controller' => 'support_tickets', 'action' => 'listtickets', 'admin' => true));
	}
}
