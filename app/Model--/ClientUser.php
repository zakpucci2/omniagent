<?php

class ClientUser extends AppModel {

	public $name = 'ClientUser';
	public $cacheQueries = false;
	// public $useDbConfig = 'super_admin';
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'client_id'
		)
	);

}
