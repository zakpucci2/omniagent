<?php

App::uses('CakeTime', 'Utility');

class Notification extends AppModel {

	public $name = 'Notification';
	public $cacheQueries = false;
	public $belongsTo = array(
		'NotificationType' => array(
			'className' => 'NotificationType',
			'foreignKey' => 'notification_type_id'
		)
	);
	public $hasMany = array(
		'UserNotification' => array(
			'className' => 'UserNotification',
			'foreignKey' => 'notification_id',
		),
	);
	public $validate = array(
		'subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter notification subject.'
			),
		),
		'body' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter notification body.'
			),
		),
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {

			if (isset($val['Notification']['created'])) {
				$results[$key]['Notification']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Notification']['created']));
				$results[$key]['Notification']['date'] = CakeTime::format($val['Notification']['created'], Configure::read('DATETIME_FORMAT.DateTime'));
			}
		}
		return $results;
	}

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['body'])) {
			$this->data[$this->alias]['body'] = strip_tags($this->data[$this->alias]['body']);
		}
		return true;
	}

    public function sendNewNotification($notificationType = 1, $notification = array()) {
        App::uses('CakeSession', 'Model/Datasource');
        App::import('Model', 'UserNotification');
        $this->UserNotification = new UserNotification();
        $notificationData['Notification']['notification_type_id'] = $notificationType;
        $notificationData['Notification']['subject'] = $notification['subject'];
        $notificationData['Notification']['body'] = $notification['body'];
        if($this->save($notificationData)) {
            $userNotificationData['UserNotification']['notification_id'] = $this->id;
            $userNotificationData['UserNotification']['sender_id'] = CakeSession::read('User.User.id');
            $userNotificationData['UserNotification']['receiver_id'] = $notification['receiver_id'];
            $this->UserNotification->save($userNotificationData);
        }

    }
}
