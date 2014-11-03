<?php

class UserPost extends AppModel {

	public $name = 'UserPost';
	public $cacheQueries = false;

	public $belongsTo = array(
		'Client' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		)		
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['content'])) {
			$this->data[$this->alias]['content'] = strip_tags($this->data[$this->alias]['content']);
		}
		return true;
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['UserPost']['created'])) {
				$results[$key]['UserPost']['ago'] = CakeTime::timeAgoInWords(strtotime($val['UserPost']['created']));
				$results[$key]['UserPost']['date'] = CakeTime::format($val['UserPost']['created'], Configure::read('DATETIME_FORMAT.DateTime'));
			}
		}
		return $results;
	}	
}
