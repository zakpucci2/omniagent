<?php

App::uses('CakeTime', 'Utility');

class AdminTeam extends AppModel {

	public $name = 'AdminTeam';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'full_name', 'phone', 'profile_photo')
		),
		'TeamLeader' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email', 'full_name')
		)
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {

			if (isset($val['AdminTeam']['modified'])) {
				$results[$key]['AdminTeam']['ago'] = CakeTime::timeAgoInWords(strtotime($val['AdminTeam']['modified']));
			}
		}
		return $results;
	}

}
