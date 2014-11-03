<?php

App::uses('CakeTime', 'Utility');

class Task extends AppModel {

	public $name = 'Task';
	public $cacheQueries = false;
	public $hasMany = array(
		'UserTask' => array(
			'className' => 'UserTask',
			'conditions' => array('UserTask.is_active' => '1'),
			'order' => 'UserTask.created DESC'
		)
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {

			if (isset($val['Task']['modified'])) {
				$results[$key]['Task']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Task']['modified']));
			}
		}
		return $results;
	}

	public $validate = array(
		'name' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter task name.'
			)
		),
		'icon_image' => array(
			'rule1' => array(
				'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
				'message' => 'Invalid image file type. Only .png, .jpg, .gig format allowed'
			)
		),
		'price_type' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select price type.'
			)
		),
		'price' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter task price.'
			),
			'rule2' => array(
				'rule' => array('money', 'left'),
				'message' => 'Please supply a valid monetary amount.'
			)
		),
		'description' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter task description.'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['description'])) {
			$this->data[$this->alias]['description'] = strip_tags($this->data[$this->alias]['description']);
		}
		return true;
	}

}
