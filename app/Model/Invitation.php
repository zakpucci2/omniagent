<?php

App::uses('CakeTime', 'Utility');

class Invitation extends AppModel {

	public $name = 'Invitation';
	public $cacheQueries = false;
	public $virtualFields = array(
		'full_name' => 'CONCAT(Invitation.first_name, " ", Invitation.last_name)'
	);
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'invited_by',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		)/* ,
		'AssociatedAdmin' => array(
			'className' => 'User',
			'foreignKey' => 'associated_admin_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		) */
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Invitation']['modified'])) {
				$results[$key]['Invitation']['ago'] = CakeTime::timeAgoInWords(strtotime($val['Invitation']['modified']));
			}
		}
		return $results;
	}

	public $validate = array(
		'first_name' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter first name.'
			),
			'rule2' => array(
				'rule' => '/^[a-zA-Z ]*$/',
				'message' => 'First name only letters allowed'
			),
		),
		'last_name' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter last name.'
			),
			'rule2' => array(
				'rule' => '/^[a-zA-Z ]*$/',
				'message' => 'Last name only letters allowed'
			),
		),
		'email' => array(
			'rule1' => array(
				'rule' => 'email',
				//'required' => true,
				'message' => 'Please enter correct email address.'
			),
			'rule2' => array(
				//'rule' => array('uniqueEmailvalid'),
				'rule' => 'isUnique',
				'message' => 'Please enter another email, given email address already exists.'
			),
			'rule3' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter email address.'
			)
		)
	);

	public function getUnreadInvites() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'Invitation.is_superadmin_approved' => 0
		);
		$unreadInvites = $this->find(
			'all', 
			array(
				'conditions' => $condition,
				'order' => 'Invitation.id DESC'
			)
		);
		return $unreadInvites;
	}
	
	public function getNewInvitesCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$condition = array(
			'Invitation.is_superadmin_approved' => 0
		);
		$newInvitesCount = $this->find('count', array(
			'conditions' => $condition
		));
		return $newInvitesCount;
	}
}
