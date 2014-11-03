<?php

class UserOffer extends AppModel {

	public $name = 'UserOffer';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

}
