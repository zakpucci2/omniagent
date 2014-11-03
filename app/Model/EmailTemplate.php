<?php

class EmailTemplate extends AppModel {

	public $name = 'EmailTemplate';
	public $validate = array(
		'template_for' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the template title.'
			),
		),
		'mail_subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the subject line.'
			),
		),
		'mail_body' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter mail template body.'
			),
		),
	);

}
