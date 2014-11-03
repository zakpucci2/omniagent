<?php

/*
 * ***********************************************************************************
 * Newsletters Controller
 * Functionality		 :	Newsletters related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');

// App::uses('ConnectionManager', 'Model');

class NewslettersController extends AppController {

    public $name = 'Newsletters';
    public $uses = array('User', 'Newsletter');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common', 'Time', 'Fck');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function superadmin_listnewsletters() {
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', "Newsletter Templates");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        if ($currentUserSession['User']['user_type_id'] != 1) {
            $this->Session->setFlash('You donot have permission', 'default', 'message');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
        }
        $this->loadModel('Newsletter');
        $this->paginate = array(
            'conditions' => array(),
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin')
        );
        $data = $this->paginate('Newsletter');
        $this->set(compact('data'));
    }

    public function superadmin_delete($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->Newsletter->id = $id;
        if ($this->Newsletter->delete()) {
            $this->Session->setFlash('Newsletter has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Newsletter is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
    }

    public function superadmin_edit_newsletter($id = null) {
        $this->layout = 'superadmin_layout';
        $id = base64_decode($id);
        $this->set('title_for_layout', "Edit Newsletter Template");
        $this->loadModel('Newsletter');
        if ($this->request->is('post')) {
            $this->Newsletter->set($this->request->data);
            if ($this->Newsletter->validates()) {
                $this->Newsletter->id = $id;
                if ($this->Newsletter->save($this->request->data['Newsletter'], false)) {
                    $this->Session->setFlash('Newsletter has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
                }
            } else {
                $errors = $this->Newsletter->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Newsletter adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
            }
        } else {
            $this->Newsletter->id = $id;
            $this->request->data = $this->Newsletter->read();
        }
    }

    public function superadmin_add_newsletter() {
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', "Add Newsletter Template");
        $this->loadModel('Newsletter');
        if ($this->request->is('post')) {
            $this->Newsletter->set($this->request->data);
            if ($this->Newsletter->validates()) {
                if ($this->Newsletter->save($this->request->data['Newsletter'], false)) {
                    $this->Session->setFlash('Newsletter has been added successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
                }
            } else {
                $errors = $this->Newsletter->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Newsletter adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
            }
        }
    }

    public function superadmin_send_newsletter() {
        $this->layout = 'superadmin_layout';
        $this->autoRender = false;

        if ($this->request->is('ajax')) {
            if (!empty($this->request->data['id'])) {
                App::uses('CakeEmail', 'Network/Email');
                $newsletter = $this->Newsletter->getNewsLetter(base64_decode($this->request->data['id']));
                $this->set('template', $newsletter['Newsletter']['mail_body']);
                $this->loadModel('NewsletterSubscriber');
                $subscribers = $this->NewsletterSubscriber->getNewsletterSubscribers();

                if (isset($this->request->data['sendTo']) && $this->request->data['sendTo'] == 'all') {
                    $this->loadModel('User');
                    $users = $this->User->getAllUsers();
                    if (!empty($subscribers)) {
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($newsletter['Newsletter']['sender_name']) && !empty($newsletter['Newsletter']['sender_name'])) {
                            $emailSenderName = $newsletter['Newsletter']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($newsletter['Newsletter']['sender_email']) && !empty($newsletter['Newsletter']['sender_email'])) {
                            $emailSenderEmail = $newsletter['Newsletter']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->subject($newsletter['Newsletter']['mail_subject']);
                        foreach ($subscribers as $subscriber) {
                            $Email->to($subscriber['NewsletterSubscriber']['email']);
                            $Email->send($newsletter['Newsletter']['mail_body']);
                        }
                    }

                    if (!empty($users)) {
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($newsletter['Newsletter']['sender_name']) && !empty($newsletter['Newsletter']['sender_name'])) {
                            $emailSenderName = $newsletter['Newsletter']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($newsletter['Newsletter']['sender_email']) && !empty($newsletter['Newsletter']['sender_email'])) {
                            $emailSenderEmail = $newsletter['Newsletter']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->subject($newsletter['Newsletter']['mail_subject']);
                        foreach ($users as $user) {
                            $Email->to($user['User']['email']);
                            $Email->send($newsletter['Newsletter']['mail_body']);
                        }
                    }

                    if (empty($users) && empty($subscribers)) {
                        $this->Session->setFlash('No user/subscriber exists', 'default', 'message');
                        echo 'success';
                        exit;
                    } else {
                        $this->Session->setFlash('Newsletter has been sent successfully to all users/admins/subscribers', 'default', 'success');
                        echo 'success';
                        exit;
                    }
                } else {
                    if (!empty($subscribers)) {
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($newsletter['Newsletter']['sender_name']) && !empty($newsletter['Newsletter']['sender_name'])) {
                            $emailSenderName = $newsletter['Newsletter']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($newsletter['Newsletter']['sender_email']) && !empty($newsletter['Newsletter']['sender_email'])) {
                            $emailSenderEmail = $newsletter['Newsletter']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->subject($newsletter['Newsletter']['mail_subject']);
                        foreach ($subscribers as $subscriber) {
                            $Email->to($subscriber['NewsletterSubscriber']['email']);
                            $Email->send($newsletter['Newsletter']['mail_body']);
                        }
                        $this->Session->setFlash('Newsletter has been sent successfully', 'default', 'success');
                        echo 'success';
                        exit;
                    } else {
                        $this->Session->setFlash('No subscriber exists', 'default', 'message');
                        echo 'success';
                        exit;
                    }
                }
            }
        } else {
            $this->Session->setFlash('Invalid request', 'default', 'error');
            $this->redirect(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true));
        }
    }

}
