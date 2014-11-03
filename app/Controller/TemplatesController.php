<?php

App::uses('AppController', 'Controller');

// App::uses('ConnectionManager', 'Model');

class TemplatesController extends AppController {

    public $name = 'Templates';
    public $uses = array('User', 'EmailTemplate');
    public $helpers = array('Html', 'Form', 'Session', 'Fck');
    public $components = array('Email');

    public function beforeFilter() {

        parent::beforeFilter();
    }

    public function superadmin_list_template() {
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', "Email Templates");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->loadModel('EmailTemplate');
        $this->paginate = array(
            'conditions' => array(),
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin')
        );
        $data = $this->paginate('EmailTemplate');
        $this->set(compact('data'));
    }

    public function superadmin_edit_template($id = null) {
        $this->layout = 'superadmin_layout';
        $id = base64_decode($id);
        $this->set('title_for_layout', "Email Templates");
        $this->loadModel('EmailTemplate');
        if ($this->request->is('post')) {
            $this->EmailTemplate->set($this->request->data);
            if ($this->EmailTemplate->validates()) {
                $this->EmailTemplate->id = $id;
                if ($this->EmailTemplate->save($this->request->data['EmailTemplate'], false)) {
                    $this->Session->setFlash('Email template has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'templates', 'action' => 'list_template', 'superadmin' => true));
                }
            } else {
                //pr($this->User->validationErrors);echo 'error';
            }
        } else {
            $this->EmailTemplate->id = $id;
            $this->request->data = $this->EmailTemplate->read();
        }
    }

}
