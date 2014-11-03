<?php

/*
 * ***********************************************************************************
 * Users Controller
 * Functionality		 :	User related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class NotesController extends AppController {

    public $name = 'Notes';
    public $uses = array('Note', 'User');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_listnotes() {
        $this->layout = 'admin_layout';
        $this->set('title_for_layout', "My Notes");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('Note.created_by' => $id, 'OR' => array('Note.note_title LIKE' => "%{$_GET['search']}%", 'Note.note_subject LIKE' => "%{$_GET['search']}%", 'Note.note_body LIKE' => "%{$_GET['search']}%"));
        } else {
            $condition = array('Note.created_by' => $id);
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'Note.id DESC',
        );
        $data = $this->paginate('Note');
        $this->set(compact('data'));
        $this->loadModel('AdminUser');
        $condition = array('AdminUser.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1);
        $client_res = $this->AdminUser->find('list', array('conditions' => $condition, 'fields' => array('User.id', 'User.user_name'), 'order' => array('User.user_name ASC'), 'recursive' => 1));
        $this->set('client_res', $client_res);
        $str = '';
        foreach ($client_res as $key => $val):

            $str.="{value: $key, text: '$val'},";
        endforeach;
        $this->set('client', trim($str));
    }

    public function admin_addnote() {
        $this->autoRender = false;
        $error = false;
        $this->loadModel('Note');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        if ($this->request->is('post')) {
            $this->Note->set($this->request->data);
            if ($this->Note->validates()) {
                $this->Note->create();
                $this->request->data['Note']['created_by'] = $id;
                if ($this->Note->save($this->request->data['Note'], false)) {

                    $this->Session->setFlash('Note has been added successfully.', 'default', 'success');
                    $this->redirect(array('controller' => 'notes', 'action' => 'listnotes', 'admin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->Note->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Note adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'admins', 'action' => 'listnotes', 'admin' => true));
            }
        }
    }

    public function admin_deletenote($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Note');
        $this->Note->id = $id;
        if ($this->Note->delete()) {
            $this->Session->setFlash('Note has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Note is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'notes', 'action' => 'listnotes', 'admin' => true));
    }

    public function admin_editnote($id = null) {
        $currentUserSession = $this->Session->read('User');

        $PopupTitle = "Edit Note";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->Note->set($this->request->data);
            if ($this->Note->validates()) {
                $this->Note->id = $id;
                if ($this->Note->save($this->request->data, false)) {

                    $this->Session->setFlash('Note has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'notes', 'action' => 'listnotes', 'admin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->Note->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Note is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'notes', 'action' => 'listnotes', 'admin' => true));
                exit;
            }
        } else {
            $this->Note->recursive = -1;
            $this->Note->id = $id;
            $this->request->data = $this->Note->read();

            if ($this->RequestHandler->isAjax()) {

                $this->set('notes', $this->request->data);
                $this->set('_serialize', array('notes', 'PopupTitle'));
            }
        }
    }

}
