<?php

App::uses('CakeTime', 'Utility');

class Note extends AppModel {

	public $name = 'Note';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'associated_with',
			'fields' => array('User.id', 'User.first_name', 'User.last_name', 'User.user_name')
	));

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {

			if (isset($val['Note']['modified'])) {
				$results[$key]['Note']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Note']['modified']));
			}
		}
		return $results;
	}

	public $validate = array(
		'title' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter first name.'
			)
		),
		'subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter last name.'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['note_body'])) {
			$this->data[$this->alias]['note_body'] = strip_tags($this->data[$this->alias]['note_body']);
		}
		return true;
	}

}
