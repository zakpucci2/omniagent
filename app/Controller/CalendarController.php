<?php

/*
 * ***********************************************************************************
 * Calendar Controller
 * Functionality		 :	Calendar related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class CalendarController extends AppController {

    public $name = 'Calendar';
    public $uses = array('User', 'UserPost');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Common');
    public $components = array('Email', 'RequestHandler', 'Security');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', "Calendar");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
    }

}
