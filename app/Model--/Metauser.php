<?php

class Metauser extends AppModel {

	public $name = 'Metauser';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

}
