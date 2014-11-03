<?php

App::uses('CakeTime', 'Utility');

class UserNotification extends AppModel {

	public $name = 'UserNotification';
	public $cacheQueries = false;
	public $belongsTo = array(
		'Notification' => array(
			'className' => 'Notification',
			'foreignKey' => 'notification_id',
			'orders' => array('Notification.created DESC')
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
			if (isset($val['Notification']['created'])) {
				$results[$key]['Notification']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Notification']['created']));
			}
		}
		return $results;
	}

	public function getUnreadNotifications() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserNotification.receiver_id' => CakeSession::read('User.User.id'),
			'UserNotification.is_receiver_deleted <> ' => 1,
			'UserNotification.is_read <> ' => 1
		);
		$unreadNotifications = $this->find(
			'all', 
			array(
				'conditions' => $condition,
				'limit' => 20,
				'order' => 'UserNotification.id DESC'
			)
		);
		return $unreadNotifications;
	}
	
	public function getNewNotificationsCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserNotification.receiver_id' => CakeSession::read('User.User.id'),
			'UserNotification.is_receiver_deleted <> ' => 1,
			'UserNotification.is_read <> ' => 1
		);
		$newNotificationsCount = $this->find('count', array(
			'conditions' => $condition
		));
		return $newNotificationsCount;
	}

}
