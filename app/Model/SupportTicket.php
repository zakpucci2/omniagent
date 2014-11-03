<?php

App::uses('CakeTime', 'Utility');

class SupportTicket extends AppModel {

	public $name = 'SupportTicket';
	public $cacheQueries = false;
	public $validate = array(
		'subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter subject.'
			),
		),
		'message' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter message.'
			),
		),
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		),
		'Admin' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'fields' => array('id', 'user_name', 'first_name', 'last_name', 'email')
		)
	);

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['SupportTicket']['created'])) {
				$results[$key]['SupportTicket']['ago'] = CakeTime::timeAgoInWords(strtotime($val['SupportTicket']['created']));
				$results[$key]['SupportTicket']['date'] = CakeTime::format($val['SupportTicket']['created'], Configure::read('DATETIME_FORMAT.DateTime'));
				$results[$key]['SupportTicket']['close_date'] = CakeTime::format($val['SupportTicket']['closed_date'], Configure::read('DATETIME_FORMAT.DateTime'));
				$ticketStatus = Configure::read('TicketStatus');
				$results[$key]['SupportTicket']['current_status'] = $ticketStatus[$val['SupportTicket']['status']];
			}
		}
		return $results;
	}

	public function getUnreadSupportTickets() {
		App::uses('CakeSession', 'Model/Datasource');
		$userType = CakeSession::read('User.User.user_type_id');
		if($userType == Configure::read('UserType.admin')) {
			$condition = array(
				'SupportTicket.admin_id' => CakeSession::read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status' => 2
			);
		} elseif($userType == Configure::read('UserType.superadmin')) {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status' => 1
			);
		} else {
			$condition = array(
				'SupportTicket.user_id' => CakeSession::read('User.User.id'),
				'SupportTicket.is_user_deleted <> ' => 1,
				'SupportTicket.status <> ' => 5
			);
		}
		$unreadTickets = $this->find(
			'all', array(
			'conditions' => $condition,
			'limit' => 20,
			'order' => 'SupportTicket.id DESC'
			)
		);
		return $unreadTickets;
	}

	public function getNewSupportTicketsCount() {
		App::uses('CakeSession', 'Model/Datasource');
		$userType = CakeSession::read('User.User.user_type_id');
		if($userType == Configure::read('UserType.admin')) {
			$condition = array(
				'SupportTicket.admin_id' => CakeSession::read('User.User.id'),
				'SupportTicket.is_admin_deleted <> ' => 1,
				'SupportTicket.status' => 2
			);
		} elseif ($userType == Configure::read('UserType.superadmin')) {
			$condition = array(
				'SupportTicket.is_superadmin_deleted <> ' => 1,
				'SupportTicket.status' => 1
			);
		}
		$newSupportTickets = $this->find('count', array(
			'conditions' => $condition
		));
		return $newSupportTickets;
	}
}
