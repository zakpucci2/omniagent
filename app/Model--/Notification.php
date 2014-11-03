<?php

App::uses('CakeTime', 'Utility');

class Notification extends AppModel {

	public $name = 'Notification';
	public $cacheQueries = false;
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

}
