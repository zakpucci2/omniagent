<?php

class Newsletter extends AppModel {

	var $name = 'Newsletter';
	var $validate = array(
		'title' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the newsletter title.'
			),
		),
		'mail_subject' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the newsletter subject line.'
			),
		),
		'mail_body' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter newsletter template body.'
			),
		),
	);

	public function getNewsletter($newsletterId) {
		$newsletter = array();
		if (!empty($newsletterId)) {
			$newsletter = $this->find(
				'first', array(
				'fields' => array(
					'Newsletter.sender_name',
					'Newsletter.sender_email',
					'Newsletter.mail_subject',
					'Newsletter.mail_body'
				),
				'conditions' => array(
					'Newsletter.id' => (int) $newsletterId
				)
				)
			);
		}
		return $newsletter;
	}

}
