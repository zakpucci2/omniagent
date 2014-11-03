<?php

class UserService extends AppModel {

	public $name = 'UserService';
	public $cacheQueries = false;

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['service_description'])) {
			$this->data[$this->alias]['service_description'] = strip_tags($this->data[$this->alias]['service_description']);
		}
		return true;
	}

}
