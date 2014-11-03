<?php

App::uses('CakeTime', 'Utility');

class UserMessage extends AppModel {

	public $name = 'UserMessage';
	public $cacheQueries = false;
	public $validate = array(
		'receiver_user_name' => array(
			'rule1' => array(
				'rule' => 'email',
				'message' => 'Please enter correct email address.'
			),
			'rule2' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter email address.'
			)
		)
	);
	public $belongsTo = array(
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'message_id',
			'orders' => array('Message.created DESC')
		),
		'Sender' => array(
			'className' => 'User',
			'foreignKey' => 'sender_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'profile_photo')
		),
		'Receiver' => array(
			'className' => 'User',
			'foreignKey' => 'receiver_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'profile_photo')
		)
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Message']['created'])) {
				$results[$key]['Message']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Message']['created']));
			}
		}
		return $results;
	}

	public function getUnreadMessages() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserMessage.receiver_id' => CakeSession::read('User.User.id'),
			'UserMessage.is_receiver_deleted <> ' => 1,
			'UserMessage.is_read <> ' => 1
		);
		$unreadMessages = $this->find(
			'all', array(
			'conditions' => $condition,
			'limit' => 20,
			'order' => 'UserMessage.id DESC'
			)
		);
		return $unreadMessages;
	}

	public function getNewMessagesCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserMessage.receiver_id' => CakeSession::read('User.User.id'),
			'UserMessage.is_receiver_deleted <> ' => 1,
			'UserMessage.is_read <> ' => 1
		);
		$newMessagesCount = $this->find('count', array(
			'conditions' => $condition
		));
		return $newMessagesCount;
	}

	public function getNewRecievedMessages($currTime = '') {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserMessage.receiver_id' => CakeSession::read('User.User.id'),
			'UserMessage.is_receiver_deleted <> ' => 1,
			'UserMessage.is_read <> ' => 1,
			'UserMessage.created_time >= ' => date('Y-m-d H:i:s', $currTime)
		);
		$unreadMessages = $this->find(
			'all', array(
			'conditions' => $condition,
			'limit' => 20,
			'order' => 'UserMessage.id DESC'
			)
		);
		return $unreadMessages;
	}
}
