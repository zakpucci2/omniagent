<?php

App::uses('CakeTime', 'Utility');

class AdminReleaseRequest extends AppModel {

	public $name = 'AdminReleaseRequest';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'full_name')
		),
		'Admin' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'full_name')
		),
		'TeamLeader' => array(
			'className' => 'User',
			'foreignKey' => 'current_tl_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'full_name')
		)
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['AdminReleaseRequest']['created'])) {
				$results[$key]['AdminReleaseRequest']['ago'] = CakeTime::timeAgoInWords(strtotime($val['AdminReleaseRequest']['created']));
			}
		}
		return $results;
	}

	public function getUnreadAdminReleaseRequests() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserAdminReleaseRequest.current_tl_id' => CakeSession::read('User.User.id'),
			'UserAdminReleaseRequest.is_tl_deleted <> ' => 1,
			'UserAdminReleaseRequest.is_request_accepted' => 0
		);
		$unreadAdminReleaseRequests = $this->find(
			'all', 
			array(
				'conditions' => $condition,
				'limit' => 20,
				'order' => 'UserAdminReleaseRequest.id DESC'
			)
		);
		return $unreadAdminReleaseRequests;
	}
	
	public function getNewAdminReleaseRequestsCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'UserAdminReleaseRequest.current_tl_id' => CakeSession::read('User.User.id'),
			'UserAdminReleaseRequest.is_tl_deleted <> ' => 1,
			'UserAdminReleaseRequest.is_request_accepted' => 0
		);
		$newAdminReleaseRequestsCount = $this->find('count', array(
			'conditions' => $condition
		));
		return $newAdminReleaseRequestsCount;
	}

}
