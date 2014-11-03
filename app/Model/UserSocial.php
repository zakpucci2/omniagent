<?php

class UserSocial extends AppModel {

	public $name = 'UserSocial';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

}
