<?php

App::uses('CakeTime', 'Utility');

class Message extends AppModel {

	public $name = 'Message';
	public $cacheQueries = false;
	public $hasMany = array(
		'UserMessage' => array(
			'className' => 'UserMessage',
			'foreignKey' => 'message_id',
		),
	);
	public $validate = array(
		'subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter message subject.'
			),
		),
		'body' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter message body.'
			),
		),
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {

			if (isset($val['Message']['created'])) {
				$results[$key]['Message']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Message']['created']));
				$results[$key]['Message']['date'] = CakeTime::format($val['Message']['created'], Configure::read('DATETIME_FORMAT.DateTime'));
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
