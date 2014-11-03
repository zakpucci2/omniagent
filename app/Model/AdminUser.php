<?php

class AdminUser extends AppModel {

	public $name = 'AdminUser';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		)
	);
}
