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

class UsersController extends AppController {

    public $name = 'Users';
    public $uses = array('User');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common', 'Time');
    public $components = array('Email', 'RequestHandler', 'Cookie');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function login() {
        $this->layout = 'outer_user_layout';
        $this->set('title_for_layout', "Login");
        if ($this->request->is('post')) {
            $udata = $this->User->find(
                'first', array(
                'fields' => array('User.user_name'),
                'conditions' => array(
                    'User.user_name' => $this->request->data['User']['user_name'],
                    'User.is_deleted <>' => 1,
                    'User.is_blocked <>' => 1
                )
                )
            );
            if (!empty($udata)) {
                $userData = array();
                $userData['user_name'] = $udata['User']['user_name'];
                $this->Cookie->delete('UserData');
                $this->Cookie->write('UserData', $userData);
            }
            $data = $this->User->find(
                'first', array(
                'fields' => array(),
                'conditions' => array(
                    'User.password' => md5($this->request->data['User']['password']),
                    'User.user_name' => $this->request->data['User']['user_name'],
                    'User.is_deleted <>' => 1
                )
                )
            );
            if ($data) {
                if ($data['User']['is_blocked'] == 1) {
                    $this->Session->setFlash('Your account is blocked', 'errormessage');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    // we check here if client of that user is blocked or not
                    $userData = array();
                    $userData['user_name'] = $data['User']['user_name'];
                    switch ($data['User']['user_type_id']) {
                        case Configure::read('UserType.superadmin'):
                            $this->Session->write('SUPER_ADMIN', 1);
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                            break;
                        case Configure::read('UserType.user') :
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                            break;
                        case Configure::read('UserType.admin') :
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            @$this->Session->delete('Requests');
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                            break;
                    }
                }
            } else {
                $this->Session->setFlash('Username and password do not match', 'errormessage');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
            $this->request->data['User']['user_name'] = '';
            $this->request->data['User']['password'] = '';
        } else {
            // If user already logged in then redirect to dashboard
            $this->_redirectLoggedInUser();
        }
    }

    public function login_unlock() {
        $this->layout = 'outer_user_layout';
        $this->set('title_for_layout', "Login");
        if ($this->Cookie->check('UserData')) {
            $this->User->recursive = -1;
            $UserData = $this->User->findByUserName(
                $this->Cookie->read('UserData.user_name'), array('user_name', 'first_name', 'last_name', 'business_name', 'display_name', 'full_name', 'profile_photo')
            );
            $this->set('UserCachedData', $UserData);
        }
        $this->User->recursive = 1;
        if ($this->request->is('post')) {
            $data = $this->User->find(
                'first', array(
                'fields' => array(),
                'conditions' => array(
                    'User.password' => md5($this->request->data['User']['password']),
                    'User.user_name' => $this->request->data['User']['user_name'],
                    'User.is_deleted <>' => 1
                )
                )
            );
            if ($data) {
                if ($data['User']['is_blocked'] == 1) {
                    $this->Session->setFlash('Your account is blocked', 'errormessage');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    // we check here if client of that user is blocked or not
                    $userData = array();
                    $userData['user_name'] = $data['User']['user_name'];
                    switch ($data['User']['user_type_id']) {
                        case Configure::read('UserType.superadmin'):
                            $this->Session->write('SUPER_ADMIN', 1);
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                            break;
                        case Configure::read('UserType.user') :
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                            break;
                        case Configure::read('UserType.admin') :
                            $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                            $this->Session->write('User', $data);
                            $this->Cookie->delete('UserData');
                            $this->Cookie->write('UserData', $userData);
                            $this->Session->setFlash('You have been successfully logged In.', 'default', 'success');
                            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                            break;
                    }
                }
            } else {
                $this->Session->setFlash('Username and password do not match', 'errormessage');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
            $this->request->data['User']['user_name'] = '';
            $this->request->data['User']['password'] = '';
        } else {
            // If user already logged in then redirect to dashboard
            $this->_redirectLoggedInUser();
        }
    }

    public function index() {
        // If user already logged in then redirect to dashboard
        $this->_redirectLoggedInUser();
    }

    public function forgotpassword() {
        $this->layout = 'outer_user_layout';
        $this->set('title_for_layout', "Recover Password");
        if ($this->request->is('post')) {
            $this->loadModel('User');
            $data = $this->User->find(
                'first', array(
                'fields' => array(),
                'conditions' => array(
                    'User.email' => $this->request->data['User']['email'],
                    'User.user_name' => $this->request->data['User']['user_name']
                )
                )
            );

            if ($data) {
                // we check here if client of that user is blocked or not
                if ($data['User']['is_blocked'] == 1) {
                    $this->Session->setFlash('Sorry! Your account has been blocked by an administrator', 'errormessage');
                    $this->redirect(array('controller' => 'users', 'action' => 'forgotpassword'));
                } else {
                    $newpassword = substr(md5(time()), 0, 6); // to generate a password of 6 characters
                    //$encpt_newpassword = md5($newpassword);
                    $encpt_newpassword = $newpassword;
                    $this->User->id = $data['User']['id'];
                    $userdata['User']['password'] = $encpt_newpassword;
                    if ($this->User->saveField('password', $userdata['User']['password'], array('validate' => false))) {
                        $this->loadModel('EmailTemplate');
                        $link = "<a href=" . Configure::read('LOGIN_URL.URL') . ">" . Configure::read('LOGIN_URL.URL') . "</a>";

                        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 1)));
                        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#USERNAME', '#PASSWORD', '#CLICKHERE'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($data['User']['first_name']), $data['User']['user_name'], $newpassword, $link), $temp['EmailTemplate']['mail_body']);
                        $this->set('template', $temp['EmailTemplate']['mail_body']);

                        App::uses('CakeEmail', 'Network/Email');
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                            $emailSenderName = $temp['EmailTemplate']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                            $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->to($this->request->data['User']['email']);
                        $Email->subject($temp['EmailTemplate']['mail_subject']);
                        $Email->send($temp['EmailTemplate']['mail_body']);
                        $this->Session->setFlash('A new password has been sent to your mailbox.', 'errormessage');
                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    }
                }
            } else {
                $this->Session->setFlash('Username and Email do not match', 'errormessage');
                $this->redirect(array('controller' => 'users', 'action' => 'forgotpassword'));
            }
            $this->request->data['User']['user_name'] = '';
            $this->request->data['User']['email'] = '';
        }
    }

    public function logout() {
        if ($this->Session->check('User')) {
            $currentUserSession = $this->Session->read('User');
            $this->loadModel('UserNotification');
            $this->UserNotification->updateAll(
                array('UserNotification.is_current_dismiss' => 0), array(
                'UserNotification.receiver_id' => $currentUserSession['User']['id'],
                'UserNotification.is_current_dismiss' => 1,
                'UserNotification.is_read <> ' => 1
                )
            );
            $this->Session->delete('User');
            $this->Session->delete('Requests');
            $this->Session->delete('UserID');
            $this->Session->delete('UserTypeID');
            $this->Session->delete('SUPER_ADMIN');
        }
        $this->redirect(array('controller' => 'users', 'action' => 'login_unlock', 'admin' => false));
    }

    public function login_clear() {
        if ($this->Cookie->check('UserData')) {
            $this->Cookie->delete('UserData');
        }
        $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => false));
    }

    public function superadmin_dashboard() {
        $this->loadModel('User');
        $this->loadModel('Invitation');
        $this->layout = 'superadmin_layout';
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array(
                "1=1",
                'OR' => array(
                    'Invitation.first_name LIKE' => "{$_GET['search']}%",
                    'Invitation.last_name LIKE' => "{$_GET['search']}%",
                    'Invitation.request_token LIKE' => "{$_GET['search']}%",
                    'Invitation.email LIKE' => "{$_GET['search']}%"
                )
            );
        } else {
            $condition = array();
        }
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'Invitation.id DESC'
        );
        $data = $this->paginate('Invitation');
        $this->set(compact('data'));

        $user_res = $this->User->find(
            'list', array(
            'conditions' => array(
                'User.user_type_id' => Configure::read('UserType.admin'),
                'User.is_deleted' => 0,
                'User.is_blocked' => 0
            ),
            'fields' => array(
                'User.id', 'User.full_name'
            ),
            'order' => array(
                'User.first_name ASC'
            ),
            'recursive' => -1
            )
        );
        $this->set('user_res', $user_res);
        $str = '';
        foreach ($user_res as $key => $val):
            $str.="{value: $key, text: '$val'},";
        endforeach;
        $this->set('users', trim($str));
    }

    public function superadmin_addinvite() {
        $this->autoRender = false;
        $error = false;
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->loadModel('Invitation');
        if ($this->request->is('post')) {
            $this->Invitation->set($this->request->data);
            if ($this->Invitation->validates()) {
                $userName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['Invitation']['associated_admin']);
                $userData = $this->User->findByUserName($userName);
                if (!empty($userData)) {
                    $this->request->data['Invitation']['associated_admin_id'] = $userData['User']['id'];
                    $this->request->data['Invitation']['is_superadmin_approved'] = 1;
                    $this->request->data['Invitation']['request_token'] = $this->generateRandomString(12);
                    $this->Invitation->create();

                    //CODE TO FIRE AN EMAIL END
                    if ($this->Invitation->save($this->request->data['Invitation'], false)) {
                        //CODE TO FIRE AN EMAIL
                        $this->loadModel('EmailTemplate');
                        $signup_link = "<a href=" . Configure::read('SIGNUP_URL.URL') . '/' . $this->request->data['Invitation']['request_token'] . ">SIGN UP NOW!</a>";

                        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 3)));
                        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#ADMINFIRSTLASTNAME', '#CLICKHERETOSIGNUPNOW'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($this->request->data['Invitation']['first_name']), $currentUserSession['User']['first_name'] . ' ' . $currentUserSession['User']['last_name'], $signup_link), $temp['EmailTemplate']['mail_body']);
                        $this->set('template', $temp['EmailTemplate']['mail_body']);
                        if ($this->request->data['Invitation']['email'] != '') {
                            App::uses('CakeEmail', 'Network/Email');
                            $Email = new CakeEmail();
                            //$Email->template('default');
                            $Email->emailFormat('both');
                            if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                                $emailSenderName = $temp['EmailTemplate']['sender_name'];
                            } else {
                                $emailSenderName = Configure::read('SITENAME.Name');
                            }
                            if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                                $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                            } else {
                                $emailSenderEmail = Configure::read('Email.EmailSupport');
                            }
                            $Email->from(array($emailSenderEmail => $emailSenderName));
                            $Email->sender(array($emailSenderEmail => $emailSenderName));
                            $Email->to($this->request->data['Invitation']['email']);
                            $Email->subject($temp['EmailTemplate']['mail_subject']);
                            $Email->send($temp['EmailTemplate']['mail_body']);
                        }

                        $this->Session->setFlash('Invitation has been added successfully and email notification has been send.', 'default', 'success');
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                    } else {
                        $error = true;
                    }
                } else {
                    $this->Session->setFlash('Associated admin does not exist with this user name.', 'default', 'error');
                    $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->Invitation->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Invitation adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
            }
        }
    }

    public function superadmin_deleteinvitation($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Invitation->id = $id;
        if ($this->Invitation->delete()) {
            $this->Session->setFlash('Invitation has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Invitation is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
    }

    public function superadmin_resendinvitation($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Invitation->id = $id;

        $data = $this->Invitation->find('first', array('fields' => array(), 'conditions' => array('Invitation.id' => $id)));
        $invite_by = $this->User->find('first', array('fields' => array('User.first_name', 'User.last_name'), 'conditions' => array('User.id' => $data['Invitation']['invited_by'])));
        //CODE TO FIRE AN EMAIL
        $this->loadModel('EmailTemplate');
        $signup_link = "<a href=" . Configure::read('SIGNUP_URL.URL') . '/' . $data['Invitation']['request_token'] . ">SIGN UP NOW!</a>";

        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 3)));
        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#ADMINFIRSTLASTNAME', '#CLICKHERETOSIGNUPNOW'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($data['Invitation']['first_name']), $invite_by['User']['first_name'] . ' ' . $invite_by['User']['last_name'], $signup_link), $temp['EmailTemplate']['mail_body']);
        $this->set('template', $temp['EmailTemplate']['mail_body']);
        if ($data['Invitation']['email'] != '') {
            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail();
            //$Email->template('default');
            $Email->emailFormat('both');
            if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                $emailSenderName = $temp['EmailTemplate']['sender_name'];
            } else {
                $emailSenderName = Configure::read('SITENAME.Name');
            }
            if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
            } else {
                $emailSenderEmail = Configure::read('Email.EmailSupport');
            }
            $Email->from(array($emailSenderEmail => $emailSenderName));
            $Email->sender(array($emailSenderEmail => $emailSenderName));
            $Email->to($data['Invitation']['email']);
            $Email->subject($temp['EmailTemplate']['mail_subject']);
            $Email->send($temp['EmailTemplate']['mail_body']);
            $this->Session->setFlash('Invitation has been resend successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Invitation has not been resend', 'default', 'error');
        }
        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
    }

    public function superadmin_approveinvitation($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Invitation->id = $id;
        $data = $this->Invitation->find(
            'first', array(
            'fields' => array(),
            'conditions' => array('Invitation.id' => $id)
            )
        );

        $invite_by = $this->User->find(
            'first', array(
            'fields' => array('User.first_name', 'User.last_name'),
            'conditions' => array('User.id' => $data['Invitation']['invited_by'])
            )
        );

        if ($this->request->is('post')) {
            $this->request->data['Invitation']['is_superadmin_approved'] = 2;
            $this->Invitation->save($this->request->data['Invitation']);

            $this->loadModel('Notification');
            $notificationData['Notification']['subject'] = 'New Invitation Request Rejected';
            $notificationData['Notification']['body'] = $this->request->data['Invitation']['rejected_comment'];
            $savedNotification = $this->Notification->save($notificationData);
            if (!empty($savedNotification)) {
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                $this->request->data['UserNotification']['receiver_id'] = $data['Invitation']['invited_by'];
                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
            }

            $this->Session->setFlash('Invitation has been rejected successfully', 'default', 'info');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
        } else {
            $invitationData['Invitation']['is_superadmin_approved'] = 1;
            $this->Invitation->save($invitationData['Invitation']);

            $this->loadModel('Notification');
            $notificationData['Notification']['subject'] = 'New Invitation Request Approved';
            $notificationData['Notification']['body'] = "Your invitation request for (" . $data['Invitation']['email'] . ") has been approved by our system administration. For more information, please inquire with your team leader. Thank you";
            $savedNotification = $this->Notification->save($notificationData);
            if (!empty($savedNotification)) {
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                $this->request->data['UserNotification']['receiver_id'] = $data['Invitation']['invited_by'];
                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
            }
        }

        //CODE TO FIRE AN EMAIL
        $this->loadModel('EmailTemplate');
        $signup_link = "<a href=" . Configure::read('SIGNUP_URL.URL') . '/' . $data['Invitation']['request_token'] . ">SIGN UP NOW!</a>";

        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 3)));
        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#ADMINFIRSTLASTNAME', '#CLICKHERETOSIGNUPNOW'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($data['Invitation']['first_name']), $invite_by['User']['first_name'] . ' ' . $invite_by['User']['last_name'], $signup_link), $temp['EmailTemplate']['mail_body']);
        $this->set('template', $temp['EmailTemplate']['mail_body']);
        if ($data['Invitation']['email'] != '') {
            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail();
            //$Email->template('default');
            $Email->emailFormat('both');
            if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                $emailSenderName = $temp['EmailTemplate']['sender_name'];
            } else {
                $emailSenderName = Configure::read('SITENAME.Name');
            }
            if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
            } else {
                $emailSenderEmail = Configure::read('Email.EmailSupport');
            }
            $Email->from(array($emailSenderEmail => $emailSenderName));
            $Email->sender(array($emailSenderEmail => $emailSenderName));
            $Email->to($data['Invitation']['email']);
            $Email->subject($temp['EmailTemplate']['mail_subject']);
            $Email->send($temp['EmailTemplate']['mail_body']);
            $this->Session->setFlash('Invitation has been sent successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Invitation has not been sent', 'default', 'error');
        }
        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
    }

    public function changepassword() {
        $this->layout = 'login_layout';
        $this->set('title_for_layout', 'Change password');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->set('userID', $id);
        if ($this->request->is('post')) {
            $newpassword = $this->request->data['User']['password'];
            $this->User->id = $currentUserSession['User']['id'];
            if ($this->User->saveField('password', $newpassword, false)) {
                $this->Session->setFlash('Password been successfully updated.', 'default', 'success');
                switch ($currentUserSession['User']['user_type_id']) {
                    case Configure::read('UserType.superadmin'):
                        $this->Session->setFlash('Password been changed successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                        break;
                    case Configure::read('UserType.user') :
                        $this->Session->setFlash('Password been changed successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        break;
                    case Configure::read('UserType.admin') :
                        $this->Session->setFlash('Password been changed successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                        break;
                }
            } else {
                switch ($currentUserSession['User']['user_type_id']) {
                    case Configure::read('UserType.superadmin'):
                        $this->Session->setFlash('Oops! There is some problem', 'default', 'message');
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                        break;
                    case Configure::read('UserType.user') :
                        $this->Session->setFlash('Oops! There is some problem', 'default', 'message');
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        break;
                    case Configure::read('UserType.admin') :
                        $this->Session->setFlash('Oops! There is some problem', 'default', 'message');
                        $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                        break;
                }
            }
        }
    }

    public function change_profile_photo() {
        $this->layout = 'login_layout';
        $this->set('title_for_layout', 'Change Profile Photo');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->set('userID', $id);
        if ($this->request->is('post')) {
            $this->User->id = $currentUserSession['User']['id'];
            if ($this->request->data['User']['profile_photo']['name'] != '') {

                $pictureTempName = $this->request->data['User']['profile_photo']['tmp_name'];
                $pictureName = $this->request->data['User']['profile_photo']['name'];
                $pictureType = $this->request->data['User']['profile_photo']['type'];
                $ext = explode('.', $pictureName);
                $ext = end($ext);
                if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                    switch ($currentUserSession['User']['user_type_id']) {
                        case Configure::read('UserType.superadmin'):
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                            break;
                        case Configure::read('UserType.user') :
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                            break;
                        case Configure::read('UserType.admin') :
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                            break;
                    }
                } else {
                    $uploadFolder = 'profile_photo';
                    $uploadFolder150x150 = 'profile_photo150x150';
                    $uploadFolder40x40 = 'profile_photo40x40';
                    App::import('Component', 'Resize');
                    $ResizeComp = new ResizeComponent();
                    $image = $this->generateRandomString(5) . '.' . $ext;
                    $logos = array('150' => $image);
                    $dimentions = array(150 => 150);
                    list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                    foreach ($dimentions as $picWidth => $picHeight) {
                        $destination150x150 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $logos[$picWidth];
                        $ResizeComp->resize_fill($pictureTempName, $destination150x150, $picWidth, $picHeight);
                        /* if ($width <= $picWidth && $height <= $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination150x150, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                          } else if ($width > $picWidth) {
                          $ResizeComp->resize($pictureTempName, $destination150x150, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                          } else if ($height > $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination150x150, 'height', 0, $picHeight, 0, 0, 0, 0);
                          } */
                    }

                    $logos40 = array('40' => $image);
                    $dimentions40 = array(40 => 40);
                    list( $width40, $height40, $sourceType40 ) = getimagesize($pictureTempName);

                    foreach ($dimentions40 as $picWidth => $picHeight) {
                        $destination40 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $logos40[$picWidth];
                        $ResizeComp->resize_fill($pictureTempName, $destination40, $picWidth, $picHeight);
                        /* if ($width40 <= $picWidth && $height40 <= $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination40, 'as_define', $width40, $height40, 0, 0, 0, 0, 0);
                          } else if ($width40 > $picWidth) {
                          $ResizeComp->resize($pictureTempName, $destination40, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                          } else if ($height40 > $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination40, 'height', 0, $picHeight, 0, 0, 0, 0);
                          } */
                    }

                    if (is_uploaded_file($pictureTempName)) {
                        $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                        move_uploaded_file($pictureTempName, $destination);
                    }

                    $currUser = $this->User->findById($currentUserSession['User']['id']);
                    if ($this->User->saveField('profile_photo', $image, false)) {
                        $oldImage = $currUser['User']['profile_photo'];
                        if ($oldImage != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage)) {
                            unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage);
                            unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $oldImage);
                            unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $oldImage);
                        }
                        // rewrite session
                        $this->rewrite_session($currentUserSession['User']['id']);
                        switch ($currentUserSession['User']['user_type_id']) {
                            case Configure::read('UserType.superadmin'):
                                $this->Session->setFlash('Profile Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                                break;
                            case Configure::read('UserType.user') :
                                $this->Session->setFlash('Profile Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                                break;
                            case Configure::read('UserType.admin') :
                                $this->Session->setFlash('Profile Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                                break;
                        }
                    }
                }
            }
        }
    }

    public function change_cover_photo() {
        $this->layout = 'login_layout';
        $this->set('title_for_layout', 'Change Cover Photo');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->set('userID', $id);
        if ($this->request->is('post')) {
            $this->User->id = $currentUserSession['User']['id'];
            if ($this->request->data['User']['cover_photo']['name'] != '') {

                $pictureTempName = $this->request->data['User']['cover_photo']['tmp_name'];
                $pictureName = $this->request->data['User']['cover_photo']['name'];
                $pictureType = $this->request->data['User']['cover_photo']['type'];
                $ext = explode('.', $pictureName);
                $ext = end($ext);
                if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                    switch ($currentUserSession['User']['user_type_id']) {
                        case Configure::read('UserType.superadmin'):
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                            break;
                        case Configure::read('UserType.user') :
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                            break;
                        case Configure::read('UserType.admin') :
                            $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                            break;
                    }
                } else {

                    $uploadFolder = 'cover_photo';
                    App::import('Component', 'Resize');
                    $ResizeComp = new ResizeComponent();
                    $image = $this->generateRandomString(5) . '.' . $ext;
                    $logos = array('1143' => $image);
                    $dimentions = array(1143 => 278);
                    list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                    foreach ($dimentions as $picWidth => $picHeight) {
                        $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $logos[$picWidth];
                        if ($width <= $picWidth && $height <= $picHeight) {
                            $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                        } else if ($width > $picWidth) {
                            $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                        } else if ($height > $picHeight) {
                            $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                        }
                    }
                    $currUser = $this->User->findById($currentUserSession['User']['id']);
                    if ($this->User->saveField('cover_photo', $image, false)) {
                        $oldImage = $currUser['User']['cover_photo'];
                        if ($oldImage != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage)) {
                            unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage);
                        }
                        // rewrite session
                        $this->rewrite_session($currentUserSession['User']['id']);
                        switch ($currentUserSession['User']['user_type_id']) {
                            case Configure::read('UserType.superadmin'):
                                $this->Session->setFlash('Cover Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                                break;
                            case Configure::read('UserType.user') :
                                $this->Session->setFlash('Cover Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                                break;
                            case Configure::read('UserType.admin') :
                                $this->Session->setFlash('Cover Photo been changed successfully', 'default', 'success');
                                $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                                break;
                        }
                    }
                }
            }
        }
    }

    public function rewrite_session($user_id = null) {
        $data = $this->User->find(
            'first', array(
            'fields' => array(),
            'conditions' => array('User.id' => $user_id)
            )
        );

        if ($data) {
            // we check here if client of that user is blocked or not
            switch ($data['User']['user_type_id']) {
                case Configure::read('UserType.superadmin'):
                    $this->Session->write('SUPER_ADMIN', 1);
                    $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                    $this->Session->write('User', $data);
                    break;
                case Configure::read('UserType.user') :
                    $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                    $this->Session->write('UserData', $data);
                    $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                    break;
                case Configure::read('UserType.admin') :
                    $this->Session->write('UserTypeID', $data['User']['user_type_id']);
                    $this->Session->write('User', $data);
                    break;
            }
        }
    }

    /*     * **************ADMINISTRATOR MODULE UNDER SUPER ADMIN PANEL*************************** */
    /*     * ************************LIST ADMINS,CREATE NEW ADMIN,EDIT,ACTIAVTE***************** */

    public function superadmin_listadmins() {
        $currentUserSession = $this->Session->read('User');
        $this->loadModel('User');
        $this->layout = 'superadmin_layout';

        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_deleted !=' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_deleted !=' => 1);
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'User.id DESC'
        );
        $data = $this->paginate('User');
        $this->set(compact('data'));
    }

    public function superadmin_trashadmins() {
        $this->loadModel('User');
        $this->layout = 'superadmin_layout';

        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_deleted' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_deleted' => 1);
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'User.id DESC'
        );
        $data = $this->paginate('User');
        $this->set(compact('data'));
    }

    public function ajax_check_username($id = null) {
        $this->autoRender = false;
        if ($id == null) {
            $count = $this->User->find('count', array(
                'conditions' => array('User.user_name' => $this->params->query['data']['User']['user_name']),
                'recursive' => -1
            ));
        } else {
            $count = $this->User->find('count', array(
                'conditions' => array(
                    'User.id <>' => $id,
                    'User.user_name' => $this->params->query['data']['User']['user_name']
                ),
                'recursive' => -1
            ));
        }
        if ($count == 0) {
            $validate = 'true';
        } else {
            $validate = 'This username is already exist';
        }
        echo json_encode($validate);
    }

    public function ajax_check_receiptemail($id = null) {
        $this->autoRender = false;
        $this->loadModel('Metauser');
        if ($id == null) {
            $count = $this->Metauser->find('count', array(
                'conditions' => array('Metauser.receipt_email' => $this->params->query['data']['Metauser']['receipt_email']),
                'recursive' => -1
            ));
        } else {
            $count = $this->Metauser->find('count', array(
                'conditions' => array(
                    'Metauser.user_id !=' => $id,
                    'Metauser.receipt_email' => $this->params->query['data']['Metauser']['receipt_email']
                ),
                'recursive' => -1
            ));
        }
        if ($count == 0) {
            $validate = 'true';
        } else {
            $validate = 'This email is already exist';
        }
        echo json_encode($validate);
    }

    public function ajax_check_email($id = null) {
        $this->autoRender = false;
        if ($id == null) {
            $count = $this->User->find('count', array(
                'conditions' => array('User.email' => $this->params->query['data']['User']['email']),
                'recursive' => -1
            ));
        } else {
            $count = $this->User->find('count', array(
                'conditions' => array(
                    'User.id !=' => $id,
                    'User.email' => $this->params->query['data']['User']['email']
                ),
                'recursive' => -1
            ));
        }
        if ($count == 0) {
            $validate = 'true';
        } else {
            $validate = 'This email is already exist';
        }
        echo json_encode($validate);
    }

    public function ajax_check_email_invite($id = null) {
        $this->autoRender = false;
        if ($id == null) {
            $count = $this->User->find('count', array(
                'conditions' => array('User.email' => $this->params->query['data']['Invitation']['email']),
                'recursive' => -1
            ));
        } else {
            $count = $this->User->find('count', array(
                'conditions' => array(
                    'User.id !=' => $id,
                    'User.email' => $this->params->query['data']['Invitation']['email']
                ),
                'recursive' => -1
            ));
        }
        if ($count == 0) {
            $validate = 'true';
        } else {
            $validate = 'This email is already exist in the system';
        }
        echo json_encode($validate);
    }

    public function superadmin_addadmin() {
        $currentUserSession = $this->Session->read('User');
        $this->autoRender = false;
        $error = false;
        $this->loadModel('User');
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->request->data['User']['user_type_id'] = Configure::read('UserType.admin');
                $this->User->create();
                $this->request->data['User']['entry_ts'] = date('Y-m-d H:i:s');

                //CODE TO FIRE AN EMAIL END
                if ($this->User->save($this->request->data['User'], false)) {
                    if ($this->request->data['User']['profile_avatar']['name'] != '') {
                        $pictureTempName = $this->request->data['User']['profile_avatar']['tmp_name'];
                        $pictureName = $this->request->data['User']['profile_avatar']['name'];
                        $pictureType = $this->request->data['User']['profile_avatar']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Avatar not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'profile_photo';
                            $uploadFolder150x150 = 'profile_photo150x150';
                            $uploadFolder40x40 = 'profile_photo40x40';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;

                            $logos = array('150' => $image);
                            $dimentions = array(150 => 150);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination150x150 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination150x150, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            $logos40 = array('40' => $image);
                            $dimentions40 = array(40 => 40);
                            list( $width40, $height40, $sourceType40 ) = getimagesize($pictureTempName);

                            foreach ($dimentions40 as $picWidth => $picHeight) {
                                $destination40 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $logos40[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination40, $picWidth, $picHeight);
                                /* if ($width40 <= $picWidth && $height40 <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'as_define', $width40, $height40, 0, 0, 0, 0, 0);
                                  } else if ($width40 > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height40 > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            if (is_uploaded_file($pictureTempName)) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                                move_uploaded_file($pictureTempName, $destination);
                            }

                            $this->User->saveField('profile_photo', $image, false);
                        }
                    }
                    $password = $this->request->data['User']['password'];

                    //CODE TO FIRE AN EMAIL
                    $this->loadModel('EmailTemplate');
                    $link = "<a href=" . Configure::read('LOGIN_URL.URL') . ">" . Configure::read('LOGIN_URL.URL') . "</a>";

                    $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 2)));
                    $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#USERNAME', '#PASSWORD', '#CLICKHERE'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($this->request->data['User']['first_name']), $this->request->data['User']['user_name'], $password, $link), $temp['EmailTemplate']['mail_body']);
                    $this->set('template', $temp['EmailTemplate']['mail_body']);
                    if ($this->request->data['User']['email'] != '') {
                        App::uses('CakeEmail', 'Network/Email');
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                            $emailSenderName = $temp['EmailTemplate']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                            $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->to($this->request->data['User']['email']);
                        $Email->subject($temp['EmailTemplate']['mail_subject']);
                        $Email->send($temp['EmailTemplate']['mail_body']);
                    }

                    $this->Session->setFlash('Admin has been added successfully and email notification has been send.', 'default', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->User->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Admin adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
            }
        }
    }

    public function superadmin_editadmin($id = null) {
        $currentUserSession = $this->Session->read('User');

        $PopupTitle = "Edit Administrator";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            $this->User->uninvalidate('password');
            $this->User->uninvalidate('cpassword');
            if ($this->User->validates()) {
                $this->User->id = $id;
                if (isset($this->request->data['User']['is_trusted_admin']) && $this->request->data['User']['is_trusted_admin'] == 1) {
                    $this->request->data['User']['is_trusted_admin'] = 1;
                } else {
                    $this->request->data['User']['is_trusted_admin'] = 0;
                }
                if (isset($this->request->data['User']['change_password']) && !empty($this->request->data['User']['change_password'])) {
                    $arrayToSave = array('is_trusted_admin', 'first_name', 'last_name', 'user_name', 'email', 'password', 'phone', 'address_line1', 'address_line2');
                    $this->request->data['User']['password'] = $this->request->data['User']['change_password'];
                } else {
                    $arrayToSave = array('is_trusted_admin', 'first_name', 'last_name', 'user_name', 'email', 'phone', 'address_line1', 'address_line2');
                }
                if ($this->User->save($this->request->data, false, $arrayToSave)) {
                    if ($this->request->data['User']['profile_avatar']['name'] != '') {
                        $pictureTempName = $this->request->data['User']['profile_avatar']['tmp_name'];
                        $pictureName = $this->request->data['User']['profile_avatar']['name'];
                        $pictureType = $this->request->data['User']['profile_avatar']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Avatar not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'profile_photo';
                            $uploadFolder150x150 = 'profile_photo150x150';
                            $uploadFolder40x40 = 'profile_photo40x40';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;

                            $logos = array('150' => $image);
                            $dimentions = array(150 => 150);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination150x150 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination150x150, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            $logos40 = array('40' => $image);
                            $dimentions40 = array(40 => 40);
                            list( $width40, $height40, $sourceType40 ) = getimagesize($pictureTempName);

                            foreach ($dimentions40 as $picWidth => $picHeight) {
                                $destination40 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $logos40[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination40, $picWidth, $picHeight);
                                /* if ($width40 <= $picWidth && $height40 <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'as_define', $width40, $height40, 0, 0, 0, 0, 0);
                                  } else if ($width40 > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height40 > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            if (is_uploaded_file($pictureTempName)) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                                move_uploaded_file($pictureTempName, $destination);
                            }

                            $currUser = $this->User->read();
                            if ($this->User->saveField('profile_photo', $image, false)) {
                                $oldImage = $currUser['User']['profile_photo'];
                                if ($oldImage != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage)) {
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $oldImage);
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $oldImage);
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage);
                                }
                            }
                        }
                    }
                    $this->Session->setFlash('Admin has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->User->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Admin is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
                exit;
            }
        } else {
            $this->User->recursive = -1;
            $this->User->id = $id;
            $this->request->data = $this->User->read();

            if ($this->RequestHandler->isAjax()) {

                $this->set('users', $this->request->data);
                $this->set('_serialize', array('users', 'PopupTitle'));
            }
        }
    }

    public function superadmin_changepassword() {
        $currentUserSession = $this->Session->read('User');
        $PopupTitle = "Update Details";
        $id = $currentUserSession['User']['id'];
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->request->data['User']['id'] = $id;
            $this->User->set($this->request->data);
            $this->User->uninvalidate('password');
            $this->User->uninvalidate('cpassword');
            if ($this->User->validates()) {
                $this->User->id = $id;
                if (isset($this->request->data['User']['change_password']) && !empty($this->request->data['User']['change_password'])) {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'password', 'phone', 'address_line1', 'address_line2');
                    $this->request->data['User']['password'] = $this->request->data['User']['change_password'];
                } else {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'phone', 'address_line1', 'address_line2');
                }
                if ($this->User->save($this->request->data, false, $arrayToSave)) {
                    $this->Session->setFlash('Profile details has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->User->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Profile details is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                exit;
            }
        } else {
            $this->User->recursive = -1;
            $this->User->id = $id;
            $this->request->data = $this->User->read();

            if ($this->RequestHandler->isAjax()) {
                $this->set('users', $this->request->data);
                $this->set('_serialize', array('users', 'PopupTitle'));
            }
        }
    }

    public function admin_changepassword() {
        $currentUserSession = $this->Session->read('User');
        $PopupTitle = "Update Details";
        $id = $currentUserSession['User']['id'];
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->request->data['User']['id'] = $id;
            $this->User->set($this->request->data);
            $this->User->uninvalidate('password');
            $this->User->uninvalidate('cpassword');
            if ($this->User->validates()) {
                $this->User->id = $id;
                if (isset($this->request->data['User']['change_password']) && !empty($this->request->data['User']['change_password'])) {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'password', 'phone', 'address_line1', 'address_line2');
                    $this->request->data['User']['password'] = $this->request->data['User']['change_password'];
                } else {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'phone', 'address_line1', 'address_line2');
                }
                if ($this->User->save($this->request->data, false, $arrayToSave)) {
                    $this->Session->setFlash('Profile details has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->User->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Profile details is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                exit;
            }
        } else {
            $this->User->recursive = -1;
            $this->User->id = $id;
            $this->request->data = $this->User->read();

            if ($this->RequestHandler->isAjax()) {
                $this->set('users', $this->request->data);
                $this->set('_serialize', array('users', 'PopupTitle'));
            }
        }
    }

    public function superadmin_deleteadmin($id = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Delete Admin');
        $this->User->id = $id;
        $userdata = $this->User->find('first', array('fields' => array('User.email', 'User.first_name', 'User.last_name'), 'conditions' => array('User.id' => $id)));
        if ($this->User->saveField('is_deleted', 1)) {
            //CODE TO FIRE AN EMAIL 
            $this->loadModel('EmailTemplate');

            $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 6)));
            $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($userdata['User']['first_name'] . ' ' . $userdata['User']['last_name'])), $temp['EmailTemplate']['mail_body']);
            $this->set('template', $temp['EmailTemplate']['mail_body']);
            if ($userdata['User']['email'] != '') {
                App::uses('CakeEmail', 'Network/Email');
                $Email = new CakeEmail();
                //$Email->template('default');
                $Email->emailFormat('both');
                if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                    $emailSenderName = $temp['EmailTemplate']['sender_name'];
                } else {
                    $emailSenderName = Configure::read('SITENAME.Name');
                }
                if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                    $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                } else {
                    $emailSenderEmail = Configure::read('Email.EmailSupport');
                }
                $Email->from(array($emailSenderEmail => $emailSenderName));
                $Email->sender(array($emailSenderEmail => $emailSenderName));
                $Email->to($userdata['User']['email']);
                $Email->subject($temp['EmailTemplate']['mail_subject']);
                $Email->send($temp['EmailTemplate']['mail_body']);
            }
        }
        $this->Session->setFlash('Admin has been deleted successfully and email notification sent', 'default', 'success');
        $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
    }

    public function superadmin_restoreadmin($id = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Delete Admin');
        $this->User->id = $id;
        $this->User->saveField('is_deleted', 0);
        $this->Session->setFlash('Admin has been restored successfully', 'default', 'success');
        $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
    }

    public function superadmin_blockadmin($id = null, $blocktype = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Block Unblock User');

        if (isset($blocktype) && $blocktype == 'block') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 1);

            $this->Session->setFlash('Admin has been blocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
        }
        if (isset($blocktype) && $blocktype == 'unblock') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 0);
            $this->Session->setFlash('Admin has been unblocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
        }
    }

    public function superadmin_trusted_untrusted_admin($id = null, $blocktype = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Block Unblock User');

        if (isset($blocktype) && $blocktype == 'close') {
            $this->User->id = $id;
            $this->User->saveField('is_trusted_admin', 0);

            $this->Session->setFlash('Admin has been converted as Regular (less privileges) admin successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
        }
        if (isset($blocktype) && $blocktype == 'open') {
            $this->User->id = $id;
            $this->User->saveField('is_trusted_admin', 1);
            $this->Session->setFlash('Admin has been converted as Trusted (full privileges) admin successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true));
        }
    }

    public function superadmin_approveclient($id = null, $approvetype = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Approve Reject User');

        $this->User->id = $id;
        $data = $this->User->read();
        if (isset($approvetype) && $approvetype == 'approve') {
            $this->User->saveField('is_approved_client', 1);
            $this->loadModel('Notification');
            $notificationData['Notification']['subject'] = 'New Client Addition Request Accepted';
            $notificationData['Notification']['body'] = 'Your new client request for (' . $data['User']['email'] . ') has been approved by our system administration. For more information, please inquire with your team leader. Thank you.';
            $savedNotification = $this->Notification->save($notificationData);
            if (!empty($savedNotification)) {
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                $this->request->data['UserNotification']['receiver_id'] = $data['User']['invited_by'];
                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
            }
            $this->Session->setFlash('Client has been approved successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
        }
        if (isset($approvetype) && $approvetype == 'reject') {
            $this->User->saveField('is_approved_client', 2);
            $this->loadModel('Notification');
            $notificationData['Notification']['subject'] = 'New Client Request Rejected';
            $notificationData['Notification']['body'] = 'Your new client request for (' . $data['User']['email'] . ') has been rejected by our system administration. For more information, please inquire with your team leader. Thank you.';
            $savedNotification = $this->Notification->save($notificationData);
            if (!empty($savedNotification)) {
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                $this->request->data['UserNotification']['receiver_id'] = $data['User']['invited_user_id'];
                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
            }
            $this->Session->setFlash('Client has been rejected successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
        }
    }

    /*     * **************CLIENT(USER) MODULE UNDER SUPER ADMIN PANEL*************************** */
    /*     * ************************LIST CLIENT(USER),CREATE NEW CLIENT,EDIT,ACTIAVTE***************** */

    public function superadmin_listclients() {
        $this->layout = 'superadmin_layout';

        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1);
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'User.id DESC',
        );
        $data = $this->paginate('User');
        $this->set(compact('data'));

        $user_res = $this->User->find(
            'list', array(
            'conditions' => array(
                'User.user_type_id' => Configure::read('UserType.admin'),
                'User.is_deleted' => 0,
                'User.is_blocked' => 0
            ),
            'fields' => array(
                'User.id', 'User.full_name'
            ),
            'order' => array(
                'User.first_name ASC'
            ),
            'recursive' => -1
            )
        );
        $this->set('user_res', $user_res);
        $str = '';
        foreach ($user_res as $key => $val):
            $str.="{value: $key, text: '$val'},";
        endforeach;
        $this->set('admins', trim($str));
    }

    public function superadmin_trashclients() {
        $this->loadModel('User');
        $this->layout = 'superadmin_layout';

        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted' => 1);
        }


        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'User.id DESC',
        );
        $data = $this->paginate('User');
        $this->set(compact('data'));
        $this->loadModel('User');
        $admin_data = $this->User->find('list', array('conditions' => array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_deleted' => 0, 'User.is_blocked' => 0), 'fields' => array('User.id', 'User.first_name'), 'order' => array('User.first_name ASC'), 'recursive' => -1));
        $this->set('admin_data', $admin_data);
        $str = '';

        foreach ($admin_data as $key => $val):

            $str.="{value: $key, text: '$val'},";
        endforeach;
        $this->set('admins', trim($str));
    }

    public function superadmin_addclient() {
        $this->autoRender = false;
        $error = false;
        $this->loadModel('User');
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->request->data['User']['user_type_id'] = Configure::read('UserType.user');
                $this->request->data['User']['is_approved_client'] = 1;
                $this->User->create();
                $this->request->data['User']['entry_ts'] = date('Y-m-d H:i:s');
                //CODE TO FIRE AN EMAIL END
                if ($this->User->save($this->request->data['User'], false)) {
                    if ($this->request->data['User']['profile_avatar']['name'] != '') {
                        $pictureTempName = $this->request->data['User']['profile_avatar']['tmp_name'];
                        $pictureName = $this->request->data['User']['profile_avatar']['name'];
                        $pictureType = $this->request->data['User']['profile_avatar']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Avatar not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'profile_photo';
                            $uploadFolder150x150 = 'profile_photo150x150';
                            $uploadFolder40x40 = 'profile_photo40x40';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;

                            $logos = array('150' => $image);
                            $dimentions = array(150 => 150);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination150x150 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination150x150, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            $logos40 = array('40' => $image);
                            $dimentions40 = array(40 => 40);
                            list( $width40, $height40, $sourceType40 ) = getimagesize($pictureTempName);

                            foreach ($dimentions40 as $picWidth => $picHeight) {
                                $destination40 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $logos40[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination40, $picWidth, $picHeight);
                                /* if ($width40 <= $picWidth && $height40 <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'as_define', $width40, $height40, 0, 0, 0, 0, 0);
                                  } else if ($width40 > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height40 > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            if (is_uploaded_file($pictureTempName)) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                                move_uploaded_file($pictureTempName, $destination);
                            }

                            $this->User->saveField('profile_photo', $image, false);
                        }
                    }

                    $password = $this->request->data['User']['password'];

                    //CODE TO FIRE AN EMAIL
                    $this->loadModel('EmailTemplate');
                    $link = "<a href=" . Configure::read('LOGIN_URL.URL') . ">" . Configure::read('LOGIN_URL.URL') . "</a>";

                    $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 8)));
                    $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#USERNAME', '#PASSWORD', '#CLICKHERE'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($this->request->data['User']['first_name']), $this->request->data['User']['user_name'], $password, $link), $temp['EmailTemplate']['mail_body']);
                    $this->set('template', $temp['EmailTemplate']['mail_body']);
                    if ($this->request->data['User']['email'] != '') {
                        App::uses('CakeEmail', 'Network/Email');
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                            $emailSenderName = $temp['EmailTemplate']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                            $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->to($this->request->data['User']['email']);
                        $Email->subject($temp['EmailTemplate']['mail_subject']);
                        $Email->send($temp['EmailTemplate']['mail_body']);
                    }

                    $this->Session->setFlash('Client(User) has been added successfully.', 'default', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->User->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Client(User) adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
            }
        }
    }

    public function superadmin_editclient($id = null) {
        $currentUserSession = $this->Session->read('User');

        $PopupTitle = "Edit Client(User)";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            $this->User->uninvalidate('password');
            $this->User->uninvalidate('cpassword');
            if ($this->User->validates()) {
                $this->User->id = $id;
                if (isset($this->request->data['User']['change_password']) && !empty($this->request->data['User']['change_password'])) {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'password', 'phone', 'address_line1', 'address_line2', 'business_name', 'website');
                    $this->request->data['User']['password'] = $this->request->data['User']['change_password'];
                } else {
                    $arrayToSave = array('first_name', 'last_name', 'user_name', 'email', 'phone', 'address_line1', 'address_line2', 'business_name', 'website');
                }
                if ($this->User->save($this->request->data, false, $arrayToSave)) {
                    if ($this->request->data['User']['profile_avatar']['name'] != '') {
                        $pictureTempName = $this->request->data['User']['profile_avatar']['tmp_name'];
                        $pictureName = $this->request->data['User']['profile_avatar']['name'];
                        $pictureType = $this->request->data['User']['profile_avatar']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Avatar not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'profile_photo';
                            $uploadFolder150x150 = 'profile_photo150x150';
                            $uploadFolder40x40 = 'profile_photo40x40';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;

                            $logos = array('150' => $image);
                            $dimentions = array(150 => 150);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination150x150 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination150x150, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            $logos40 = array('40' => $image);
                            $dimentions40 = array(40 => 40);
                            list( $width40, $height40, $sourceType40 ) = getimagesize($pictureTempName);

                            foreach ($dimentions40 as $picWidth => $picHeight) {
                                $destination40 = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $logos40[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination40, $picWidth, $picHeight);
                                /* if ($width40 <= $picWidth && $height40 <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'as_define', $width40, $height40, 0, 0, 0, 0, 0);
                                  } else if ($width40 > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height40 > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination40, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }

                            if (is_uploaded_file($pictureTempName)) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                                move_uploaded_file($pictureTempName, $destination);
                            }

                            $currUser = $this->User->read();
                            if ($this->User->saveField('profile_photo', $image, false)) {
                                $oldImage = $currUser['User']['profile_photo'];
                                if ($oldImage != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage)) {
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage);
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder40x40 . '/' . $oldImage);
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder150x150 . '/' . $oldImage);
                                }
                            }
                        }
                    }

                    $this->Session->setFlash('Client(User) has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->User->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Client(User) is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
                exit;
            }
        } else {
            // $this->User->recursive = -1;
            $this->User->id = $id;
            $this->request->data = $this->User->read();
            if ($this->RequestHandler->isAjax()) {
                $this->set('users', $this->request->data);
                $this->set('_serialize', array('users', 'PopupTitle'));
            }
        }
    }

    public function superadmin_blockclient($id = null, $blocktype = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Block Unblock Client');

        if (isset($blocktype) && $blocktype == 'block') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 1);

            $this->Session->setFlash('Client(User) has been blocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
        }
        if (isset($blocktype) && $blocktype == 'unblock') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 0);
            $this->Session->setFlash('Client(User) has been unblocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
        }
    }

    public function superadmin_deleteclient($id = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Delete User');
        $this->User->id = $id;
        $userdata = $this->User->find('first', array('fields' => array('User.email', 'User.first_name', 'User.last_name'), 'conditions' => array('User.id' => $id)));
        if ($this->User->saveField('is_deleted', 1)) {
            //CODE TO FIRE AN EMAIL 
            $this->loadModel('EmailTemplate');

            $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 7)));
            $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($userdata['User']['first_name'] . ' ' . $userdata['User']['last_name'])), $temp['EmailTemplate']['mail_body']);
            $this->set('template', $temp['EmailTemplate']['mail_body']);
            if ($userdata['User']['email'] != '') {
                App::uses('CakeEmail', 'Network/Email');
                $Email = new CakeEmail();
                //$Email->template('default');
                $Email->emailFormat('both');
                if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                    $emailSenderName = $temp['EmailTemplate']['sender_name'];
                } else {
                    $emailSenderName = Configure::read('SITENAME.Name');
                }
                if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                    $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                } else {
                    $emailSenderEmail = Configure::read('Email.EmailSupport');
                }
                $Email->from(array($emailSenderEmail => $emailSenderName));
                $Email->sender(array($emailSenderEmail => $emailSenderName));
                $Email->to($userdata['User']['email']);
                $Email->subject($temp['EmailTemplate']['mail_subject']);
                $Email->send($temp['EmailTemplate']['mail_body']);
            }
        }
        $this->Session->setFlash('Client(User) has been deleted successfully', 'default', 'success');
        $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
    }

    public function superadmin_restoreclient($id = null) {
        $id = base64_decode($id);
        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', 'Restore User');
        $this->User->id = $id;
        $this->User->saveField('is_deleted', 0);
        $this->Session->setFlash('Client(User) has been restored successfully', 'default', 'success');
        $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true));
    }

    public function signup($uniqueKey = null) {
        $this->layout = 'outer_user_layout';
        $this->set('title_for_layout', 'Sign Up');
        if (empty($uniqueKey)) {
            $this->Session->setFlash('Invalid request code. Please contact with administrator to send you invitation.', 'errormessage');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        $this->loadModel('Invitation');
        $userInfo = $this->Invitation->find('first', array(
            'conditions' => array(
                'Invitation.request_token' => trim($uniqueKey),
                'Invitation.is_used' => 0
            )
        ));
        if (empty($userInfo)) {
            $this->Session->setFlash('Invalid request code. Please contact with administrator to send you invitation.', 'errormessage');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $this->set('userInfo', $userInfo);
        $this->set('uniqueKey', $uniqueKey);
        $this->loadModel('User');
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $password = $this->request->data['User']['password'];
                $this->request->data['User']['user_type_id'] = Configure::read('UserType.user');
                $this->User->create();
                $this->request->data['User']['entry_ts'] = date('Y-m-d H:i:s');

                //CODE TO FIRE AN EMAIL END
                if ($this->User->save($this->request->data['User'], false)) {
                    $this->Invitation->id = $userInfo['Invitation']['id'];
                    $this->Invitation->saveField('is_used', 1, array('validate' => false));
                    $this->Invitation->saveField('is_request_accepted', 1, array('validate' => false));
                    $this->Invitation->saveField('request_token', '', array('validate' => false));
                    //CODE TO FIRE AN EMAIL
                    $this->loadModel('EmailTemplate');
                    $link = "<a href=" . Configure::read('LOGIN_URL.URL') . ">" . Configure::read('LOGIN_URL.URL') . "</a>";

                    $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 4)));
                    $temp['EmailTemplate']['mail_body'] = str_replace(
                        array(
                        '../../..',
                        '#FIRSTNAME',
                        '#USERNAME',
                        '#PASSWORD',
                        '#CLICKHERE'
                        ), array(
                        Configure::read('FULL_BASE_URL.URL'),
                        ucwords($this->request->data['User']['first_name']),
                        $this->request->data['User']['user_name'],
                        $password,
                        $link
                        ), $temp['EmailTemplate']['mail_body']
                    );
                    $this->set('template', $temp['EmailTemplate']['mail_body']);
                    if ($this->request->data['User']['email'] != '') {
                        App::uses('CakeEmail', 'Network/Email');
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($temp['EmailTemplate']['sender_name']) && !empty($temp['EmailTemplate']['sender_name'])) {
                            $emailSenderName = $temp['EmailTemplate']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($temp['EmailTemplate']['sender_email']) && !empty($temp['EmailTemplate']['sender_email'])) {
                            $emailSenderEmail = $temp['EmailTemplate']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->to($this->request->data['User']['email']);
                        $Email->subject($temp['EmailTemplate']['mail_subject']);
                        $temp['EmailTemplate']['mail_body'];
                        $Email->send($temp['EmailTemplate']['mail_body']);
                    }

                    $adminName = $this->User->find('first', array('conditions' => array('User.id' => 1)));
                    $tempAdmin = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 5)));
                    $tempAdmin['EmailTemplate']['mail_body'] = str_replace(
                        array(
                        '../../..',
                        '#ADMINNAME',
                        '#FULLNAME',
                        '#EMAIL',
                        '#USERNAME',
                        '#INVITEDBY',
                        '#INVITEDDATE'
                        ), array(
                        Configure::read('FULL_BASE_URL.URL'),
                        ucwords($adminName['User']['first_name']),
                        ucwords($this->request->data['User']['first_name'] . ' ' . $this->request->data['User']['last_name']),
                        $this->request->data['User']['email'],
                        $this->request->data['User']['user_name'],
                        ucwords($userInfo['User']['first_name'] . ' ' . $userInfo['User']['last_name']),
                        date('d/M/Y', strtotime($userInfo['Invitation']['created']))
                        ), $tempAdmin['EmailTemplate']['mail_body']
                    );
                    $this->set('template', $tempAdmin['EmailTemplate']['mail_body']);
                    if ($adminName['User']['email'] != '') {
                        App::uses('CakeEmail', 'Network/Email');
                        $Email = new CakeEmail();
                        //$Email->template('default');
                        $Email->emailFormat('both');
                        if (isset($tempAdmin['EmailTemplate']['sender_name']) && !empty($tempAdmin['EmailTemplate']['sender_name'])) {
                            $emailSenderName = $tempAdmin['EmailTemplate']['sender_name'];
                        } else {
                            $emailSenderName = Configure::read('SITENAME.Name');
                        }
                        if (isset($tempAdmin['EmailTemplate']['sender_email']) && !empty($tempAdmin['EmailTemplate']['sender_email'])) {
                            $emailSenderEmail = $tempAdmin['EmailTemplate']['sender_email'];
                        } else {
                            $emailSenderEmail = Configure::read('Email.EmailSupport');
                        }
                        $Email->from(array($emailSenderEmail => $emailSenderName));
                        $Email->sender(array($emailSenderEmail => $emailSenderName));
                        $Email->to($adminName['User']['email']);
                        $Email->subject($tempAdmin['EmailTemplate']['mail_subject']);
                        $tempAdmin['EmailTemplate']['mail_body'];
                        $Email->send($tempAdmin['EmailTemplate']['mail_body']);
                    }

                    $this->Session->delete('User');
                    $this->Session->delete('UserID');
                    $this->Session->delete('UserTypeID');
                    $this->Session->delete('SUPER_ADMIN');
                    $this->Session->setFlash('Congratulations! Your account has been created successfully.', 'errormessage');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->User->validationErrors;
                $this->Session->setFlash('Unable to create your profile. Please contact site administrator for more details.', 'errormessage');
                $this->redirect(array('controller' => 'users', 'action' => 'signup'));
            }
        }
    }

    public function dashboard() {
        $this->layout = 'user_layout';
        $this->loadModel('UserTask');
        
        $task = $this->UserTask->find('all', array(
            'conditions' => array(
                'UserTask.client_id' => $this->Session->read('User.User.id'),
                'UserTask.status_completed <' => 100,
                'UserTask.is_completed ' => 0,
                'UserTask.is_active ' => 1,
                'UserTask.is_deleted ' => 0
            ),
            //'fields' => array('UserTask.task_title', 'UserTask.created'),
            'order' => array('UserTask.created DESC')
        ));
        $this->set('taskData', $task);
        $userId = $this->Session->read('User.User.id');
        $this->set('title_for_layout', 'User Dashboard');
    }
    
    public function updatetask(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $userData = $this->User->findById($this->Session->read('User.User.id'));
            $taskId = $this->request->data['taskId'];
            $taskcompleted = $this->request->data['value'];
            $compval = (isset($this->request->data['type']) && $this->request->data['type']=='complete') ? '1' : '0';
            $this->loadModel('UserTask');
            $taskData = $this->UserTask->findById($taskId);
            switch($taskcompleted){
                case '1':
                    $this->UserTask->id = $taskId;
                    $this->UserTask->saveField("task1_completed", $compval, false);
                    $infoval = $taskData['Task']['info1'];
                    //$upArray['UserTask']['task1_completed'] = $compval;
                    break;
                case '2':
                    $this->UserTask->id = $taskId;
                    $this->UserTask->saveField("task2_completed", $compval, false);
                    $infoval = $taskData['Task']['info2'];
                    break;
                case '3':
                    $this->UserTask->id = $taskId;
                    $this->UserTask->saveField("task3_completed", $compval, false);
                    $infoval = $taskData['Task']['info3'];
                    break;
                case '4':
                    $this->UserTask->id = $taskId;
                    $this->UserTask->saveField("task4_completed", $compval, false);
                    $infoval = $taskData['Task']['info4'];
                    break;
                case '5':
                    $this->UserTask->id = $taskId;
                    $this->UserTask->saveField("task5_completed", $compval, false);
                    $infoval = 'File upload';
                    break;
            }
            
            $taskstatus = ($compval=='1') ? 'completed' : 'reopened';
            $this->loadModel("UserNotification");
            $user_noti = array(
                    "Notification" => array(
                        "subject" => "ToDo ". ucfirst($taskstatus),
                        "body" => sprintf('"%s %s" has %s the required info "%s" ToDo of task "%s"', $userData["User"]["first_name"], $userData["User"]["last_name"],$taskstatus,$infoval,$taskData['UserTask']['task_title']),
                        "notification_type_id" => (($compval=='1') ? 20 : 21)
                    ),
                    "UserNotification" => array(
                        "sender_id" => $this->Session->read('User.User.id'),
                        "receiver_id" => $taskData["UserTask"]["admin_id"]
                    )
            );
            
            $this->UserNotification->saveAll($user_noti);
            echo true;
        }
    }

    public function editprofile() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', 'Edit Profile');

        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if (!isset($this->request->data['User']['password'])) {
                $this->User->uninvalidate('password');
                $this->User->uninvalidate('cpassword');
            }
            if ($this->User->validates()) {
                $this->User->id = $this->Session->read('User.User.id');
                $this->request->data['User']['id'] = $this->Session->read('User.User.id');
                if (!empty($this->request->data['Metauser']) || !empty($this->request->data['UserService']) || !empty($this->request->data['UserSocial']) || !empty($this->request->data['UserOffer'])) {
                    if (!empty($this->request->data['Metauser'])) {
                        $this->request->data['Metauser']['user_id'] = $this->Session->read('User.User.id');
                        if (isset($this->request->data['Metauser']['remember_card']) && $this->request->data['Metauser']['remember_card'] == 1) {
                            // No action required
                        } else {
                            $this->request->data['Metauser']['card_type'] = '';
                            $this->request->data['Metauser']['card_number'] = '';
                            $this->request->data['Metauser']['cvv'] = '';
                            $this->request->data['Metauser']['name_on_card'] = '';
                            $this->request->data['Metauser']['receipt_email'] = '';
                            $this->request->data['Metauser']['zip_code'] = '';
                            $this->request->data['Metauser']['remember_card'] = '';
                        }
                    }
                    if (!empty($this->request->data['UserService'])) {
                        $this->request->data['UserService'][0]['user_id'] = $this->Session->read('User.User.id');
                        $this->request->data['UserService'][1]['user_id'] = $this->Session->read('User.User.id');
                        $this->request->data['UserService'][2]['user_id'] = $this->Session->read('User.User.id');
                    }
                    if (!empty($this->request->data['UserSocail'])) {
                        $this->request->data['UserSocail']['user_id'] = $this->Session->read('User.User.id');
                    }
                    if (!empty($this->request->data['UserOffer'])) {
                        $this->request->data['UserOffer'][0]['user_id'] = $this->Session->read('User.User.id');
                        $this->request->data['UserOffer'][1]['user_id'] = $this->Session->read('User.User.id');
                        $this->request->data['UserOffer'][2]['user_id'] = $this->Session->read('User.User.id');
                        $this->request->data['UserOffer'][3]['user_id'] = $this->Session->read('User.User.id');
                    }
                    if ($this->User->saveAssociated($this->request->data)) {
                        $this->Session->setFlash('Profile has been updated successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'users', 'action' => 'editprofile'));
                    }
                } else {
                    if ($this->User->save($this->request->data, false)) {
                        $this->Session->setFlash('Profile has been updated successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'users', 'action' => 'editprofile'));
                    }
                }
            } else {
                $str = '';
                foreach ($this->User->validationErrors as $error) {
                    $str .= $error[0] . '<br/>';
                }
                $this->Session->setFlash('Profile is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'editprofile'));
            }
        } else {
            if ($this->Session->check('User')) {
                $userId = $this->Session->read('User.User.id');
                $this->User->id = $userId;
                $this->request->data = $this->User->read();
            } else {
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }
    }

    private function _redirectLoggedInUser() {
        // If user already logged in then redirect to dashboard
        if ($this->Session->check('User')) {
            $userLoginData = $this->Session->read('User');
            if (!empty($userLoginData) && isset($userLoginData['User']['user_type_id'])) {
                // we check here if client of that user is blocked or not
                switch ($userLoginData['User']['user_type_id']) {
                    case Configure::read('UserType.superadmin'):
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true));
                        break;
                    case Configure::read('UserType.user') :
                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        break;
                    case Configure::read('UserType.admin') :
                        $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                        break;
                }
            }
        }
    }

    public function findUser() {
        $this->User->recursive = 0;
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->layout = 'ajax';
            if ((int) $this->Session->read('User.User.user_type_id') === (int) Configure::read('UserType.user')) {
                $conditions = array(
                    'OR' => array(
                        'User.first_name LIKE ' => $this->request->query['q'] . '%',
                        'User.last_name LIKE ' => $this->request->query['q'] . '%',
                        'User.user_name LIKE ' => $this->request->query['q'] . '%',
                        'User.email LIKE ' => $this->request->query['q'] . '%',
                    ),
                    'User.id <> ' => $this->Session->read('User.User.id'),
                    'User.user_type_id <> ' => Configure::read('UserType.superadmin'),
                    'User.is_deleted' => 0,
                    'User.is_blocked' => 0
                );
            } else {
                $conditions = array(
                    'OR' => array(
                        'User.first_name LIKE ' => $this->request->query['q'] . '%',
                        'User.last_name LIKE ' => $this->request->query['q'] . '%',
                        'User.user_name LIKE ' => $this->request->query['q'] . '%',
                        'User.email LIKE ' => $this->request->query['q'] . '%',
                    ),
                    'User.id <> ' => $this->Session->read('User.User.id'),
                    'User.is_deleted' => 0,
                    'User.is_blocked' => 0
                );
            }
            $results = $this->User->find('all', array(
                'fields' => array('user_name'),
                //remove the leading '%' if you want to restrict the matches more
                'conditions' => $conditions
            ));
            // pr($this->User->getLastQuery());
            if (isset($results) && count($results) > 0) {
                foreach ($results as $result) {
                    echo $result['User']['user_name'] . "\n";
                }
            }
        }
    }

    public function username_exists() {
        $this->autoRender = false;
        $count = $this->User->find('count', array(
            'conditions' => array(
                'User.user_name' => $this->params->query['data']['Message']['receiver_user_name']
            ),
            'recursive' => -1
        ));
        if ($count == 0) {
            $validate = 'Username or Email does not exists.';
        } else {
            $validate = 'true';
        }
        echo json_encode($validate);
    }

    public function usernameExists() {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $count = $this->User->find('count', array(
                'conditions' => array(
                    'User.user_name' => $this->request->data['userName']
                ),
                'recursive' => -1
            ));
            if ($count == 0) {
                $validate['msg'] = false;
            } else {
                $validate['msg'] = true;
            }
            echo json_encode($validate);
        }
    }

    public function findAdminUsers() {
        $this->User->recursive = 0;
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->layout = 'ajax';
            $conditions = array(
                'OR' => array(
                    'User.first_name LIKE ' => $this->request->query['q'] . '%',
                    'User.last_name LIKE ' => $this->request->query['q'] . '%',
                    'User.user_name LIKE ' => $this->request->query['q'] . '%',
                    'User.email LIKE ' => $this->request->query['q'] . '%',
                ),
                'User.id <> ' => $this->Session->read('User.User.id'),
                'User.user_type_id' => (int) Configure::read("UserType.admin"),
                'User.is_deleted' => 0,
                'User.is_blocked' => 0
            );
            $results = $this->User->find('all', array(
                'fields' => array('user_name'),
                //remove the leading '%' if you want to restrict the matches more
                'conditions' => $conditions
            ));
            // pr($this->User->getLastQuery());
            if (isset($results) && count($results) > 0) {
                foreach ($results as $result) {
                    echo $result['User']['user_name'] . "\n";
                }
            }
        }
    }

    public function assignAdmin() {
        $this->autoRender = false;
        $currentUserSession = $this->Session->read('User');
        $this->layout = 'ajax';
        if (!empty($this->request->data['value'])) {
            $this->Invitation->id = $this->request->data['pk'];
            $data['Invitation']['associated_admin_id'] = $this->request->data['value'];
            $this->Invitation->save($data['Invitation'], false);
        }
    }

    public function assignAssociatedAdmin() {
        $this->autoRender = false;
        $currentUserSession = $this->Session->read('User');
        $this->layout = 'ajax';
        if (!empty($this->request->data['value'])) {
            $this->User->id = $this->request->data['pk'];
            $userData = $this->User->findById($this->request->data['pk']);
            $data['User']['associated_admin_id'] = $this->request->data['value'];
            $this->User->save($data['User'], false);
            $this->loadModel('AdminUser');
            $found = $this->AdminUser->hasAny(
                array('AdminUser.user_id' => $userData['User']['id'])
            );
            if (!empty($found)) {
                $this->AdminUser->updateAll(
                    array('AdminUser.admin_id' => $this->request->data['value']), array('AdminUser.user_id' => $userData['User']['id'])
                );
            } else {
                $this->AdminUser->create();
                $adminData['AdminUser']['admin_id'] = $this->request->data['value'];
                $adminData['AdminUser']['user_id'] = $userData['User']['id'];
                $this->AdminUser->save($adminData);
            }

            /*             * ************************************ */

            $this->loadModel("UserNotification");
            $user_noti = array(
                0 => array(
                    "Notification" => array(
                        "subject" => "New client",
                        "body" => sprintf('"%s %s" now added in your client list', $userData["User"]["first_name"], $userData["User"]["last_name"]),
                        "notification_type_id" => 0
                    ),
                    "UserNotification" => array(
                        "sender_id" => $currentUserSession["User"]["id"],
                        "receiver_id" => $this->request->data['value']
                    )
                )
            );

            foreach ($user_noti as $val) {
                $this->UserNotification->saveAll($val);
            }
            /*             * ************************************ */
        }
    }

    public function latestInvites() {
        $timeStart = time();
        $this->layout = 'ajax';
        $this->autoRender = false;
        $userId = $this->Session->read('User.User.id');
        $this->loadModel('Invitation');
        if ($this->request->is('post')) {
            if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
                $timestamp = $this->request->data['timestamp'];
            } else {
                // get current database time
                $nowTime = $this->Invitation->getMySQLNowTimestamp();
                $timestamp = $nowTime[0][0]['timestamp'];
            }
        } else {
            $nowTime2 = $this->Invitation->getMySQLNowTimestamp();
            $timestamp = $nowTime2[0][0]['timestamp'];
        }
        $newData = false;
        $invites = array();

        // loop while there is no new data and is running for less than 20 seconds
        while (!$newData && (time() - $timeStart) < 30) {
            // check for new data
            $invitesCount = $this->Invitation->getNewInvitesCount();
            // pr($this->Message->getLastQuery());
            if (isset($invitesCount)) {
                $invites[] = $invitesCount;
                $newData = true;
            }
            // let the server rest for a while
            usleep(1000000);
        }
        // get current database time
        $nowTime3 = $this->Invitation->getMySQLNowTimestamp();
        $timestamp = $nowTime3[0][0]['timestamp'];

        // output
        $data = array('invites' => $invites, 'timestamp' => $timestamp);
        echo json_encode($data);
        exit;
    }

    public function latestClientInvites() {
        $timeStart = time();
        $this->layout = 'ajax';
        $this->autoRender = false;
        $userId = $this->Session->read('User.User.id');
        $this->loadModel('User');
        if ($this->request->is('post')) {
            if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
                $timestamp = $this->request->data['timestamp'];
            } else {
                // get current database time
                $nowTime = $this->User->getMySQLNowTimestamp();
                $timestamp = $nowTime[0][0]['timestamp'];
            }
        } else {
            $nowTime2 = $this->User->getMySQLNowTimestamp();
            $timestamp = $nowTime2[0][0]['timestamp'];
        }
        $newData = false;
        $clients = array();

        // loop while there is no new data and is running for less than 20 seconds
        while (!$newData && (time() - $timeStart) < 30) {
            // check for new data
            $clientsCount = $this->User->getNewInvitesCount();
            // pr($this->Message->getLastQuery());
            if (isset($clientsCount)) {
                $clients[] = $clientsCount;
                $newData = true;
            }
            // let the server rest for a while
            usleep(1000000);
        }
        // get current database time
        $nowTime3 = $this->User->getMySQLNowTimestamp();
        $timestamp = $nowTime3[0][0]['timestamp'];

        // output
        $data = array('clients' => $clients, 'timestamp' => $timestamp);
        echo json_encode($data);
        exit;
    }

    public function ajaxValidateUsername() {
        $this->autoRender = false;
        $this->layout = 'ajax';

        $validate = array();
        if (!isset($this->request->data['userPass'])) {
            $conditions = array(
                'User.user_name' => $this->request->data['userName'],
                'User.is_deleted <>' => 1,
                'User.is_approved_client <>' => 2
            );
        } else {
            $conditions = array(
                'User.user_name' => $this->request->data['userName'],
                'User.password' => md5($this->request->data['userPass']),
                'User.is_deleted <>' => 1,
                'User.is_approved_client <>' => 2
            );
        }
        $user = $this->User->find('first', array(
            'fields' => array(
                'User.profile_photo',
                'User.user_type_id',
                'User.is_deleted',
                'User.is_approved_client',
                'User.is_blocked'
            ),
            'conditions' => $conditions,
            'recursive' => -1
        ));
        if (empty($user)) {
            $validate['status'] = false;
            $validate['errormessage'] = 'Username does not exist';
        } else {
            if ($user['User']['is_blocked'] == 1) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is blocked. Please contact with administrator.';
            } elseif ($user['User']['user_type_id'] == Configure::read('UserType.user') && $user['User']['is_approved_client'] == 0) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is pending for approval from administrator.';
            } else {
                $validate['status'] = true;
                $validate['User'] = $user['User'];
            }
        }
        echo json_encode($validate);
        exit;
    }

    public function ajaxValidateUsernamePassword() {
        $this->autoRender = false;
        $this->layout = 'ajax';

        $validate = array();
        $user = $this->User->find('first', array(
            'fields' => array(
                'User.profile_photo',
                'User.user_type_id',
                'User.is_deleted',
                'User.is_approved_client',
                'User.is_blocked'
            ),
            'conditions' => array(
                'User.user_name' => $this->request->data['userName'],
                'User.password' => md5($this->request->data['userPass']),
                'User.is_deleted <>' => 1,
                'User.is_approved_client <>' => 2
            ),
            'recursive' => -1
        ));
        if (empty($user)) {
            $validate['status'] = false;
            $validate['errormessage'] = 'Username or Password does not match.';
        } else {
            if ($user['User']['is_blocked'] == 1) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is blocked. Please contact with administrator.';
            } elseif ($user['User']['user_type_id'] == Configure::read('UserType.user') && $user['User']['is_approved_client'] == 0) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is pending for approval from administrator.';
            } else {
                $validate['status'] = true;
            }
        }
        echo json_encode($validate);
    }

    public function ajaxValidateUsernameEmail() {
        $this->autoRender = false;
        $this->layout = 'ajax';

        $validate = array();
        $user = $this->User->find('first', array(
            'fields' => array(
                'User.profile_photo',
                'User.user_type_id',
                'User.is_deleted',
                'User.is_approved_client',
                'User.is_blocked'
            ),
            'conditions' => array(
                'User.user_name' => $this->request->data['userName'],
                'User.email' => $this->request->data['userEmail'],
                'User.is_deleted <>' => 1,
                'User.is_approved_client <>' => 2
            ),
            'recursive' => -1
        ));
        if (empty($user)) {
            $validate['status'] = false;
            $validate['errormessage'] = 'Username and Email does not exist';
        } else {
            if ($user['User']['is_blocked'] == 1) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is blocked. Please contact with administrator.';
            } elseif ($user['User']['user_type_id'] == Configure::read('UserType.user') && $user['User']['is_approved_client'] == 0) {
                $validate['status'] = false;
                $validate['errormessage'] = 'Your account is pending for approval from administrator.';
            } else {
                $validate['status'] = true;
            }
        }
        echo json_encode($validate);
    }

    public function updateTourStatus() {
        $this->autoRender = false;
        $this->layout = 'ajax';

        $userId = base64_decode($this->request->data['userId']);

        if (!empty($userId)) {
            $this->User->id = $userId;
            if ($this->User->saveField('is_tour_completed', 1, false)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
