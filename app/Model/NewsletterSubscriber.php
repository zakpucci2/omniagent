<?php

class NewsletterSubscriber extends AppModel {

	var $name = 'NewsletterSubscriber';
	var $validate = array(
		'name' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter name.'
			),
			'rule2' => array(
				'rule' => '/^[a-zA-Z ]*$/',
				'message' => 'Only letters are allowed in name.'
			),
		),
		'email' => array(
			'rule1' => array(
				'rule' => 'email',
				'message' => 'Please enter valid email address.'
			),
			'rule2' => array(
				//'rule' => array('uniqueEmailvalid'),
				'rule' => 'isUnique',
				'message' => 'Please enter another email, given email address already subscribed for newsletters.'
			),
			'rule3' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter email address.'
			)
		),
	);

	public function getNewsletterSubscribers() {
		$subscribers = array();
		$subscribers = $this->find(
			'all', array(
				'fields' => array(
					'NewsletterSubscriber.name',
					'NewsletterSubscriber.email'
				)
			)
		);
		return $subscribers;
	}

}
