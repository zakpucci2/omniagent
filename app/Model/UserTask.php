<?php

class UserTask extends AppModel {

	public $name = 'UserTask';
	public $cacheQueries = false;

	public $belongsTo = array(
		'Client' => array(
			'className' => 'User',
			'foreignKey' => 'client_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		),
		'Admin' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		),
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_id'
		)		
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['task_description'])) {
			$this->data[$this->alias]['task_description'] = strip_tags($this->data[$this->alias]['task_description']);
		}
		return true;
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['UserTask']['created'])) {
				$results[$key]['UserTask']['ago'] = CakeTime::timeAgoInWords(strtotime($val['UserTask']['created']));
				$results[$key]['UserTask']['date'] = CakeTime::format($val['UserTask']['created'], Configure::read('DATETIME_FORMAT.DateTime'));
			}
		}
		return $results;
	}
	
	public function getInProgressTasks() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserTask.client_id' => CakeSession::read('User.User.id'),
			'UserTask.is_deleted <> ' => 1,
			'UserTask.is_active' => 1,
			'UserTask.is_completed <> ' => 1
		);
		$inProgressTasks = $this->find(
			'all', array(
			'conditions' => $condition,
			'limit' => 20,
			'order' => 'UserTask.id DESC'
			)
		);
		return $inProgressTasks;
	}

	public function getInProgressTasksCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserTask.client_id' => CakeSession::read('User.User.id'),
			'UserTask.is_deleted <> ' => 1,
			'UserTask.is_active' => 1,
			'UserTask.is_completed <> ' => 1
		);
		$inProgressTasksCount = $this->find('count', array(
			'conditions' => $condition
		));
		return $inProgressTasksCount;
	}	
}
