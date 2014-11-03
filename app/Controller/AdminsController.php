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

class AdminsController extends AppController {

    public $name = 'Admins';
    public $uses = array('User');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_dashboard() {

        $this->layout = 'admin_layout';
        $this->loadModel('User');
        $this->loadModel('Invitation');
        $this->set('title_for_layout', "List Inivitation");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array("Invitation.invited_by" => $id, 'OR' => array('Invitation.first_name LIKE' => "{$_GET['search']}%", 'Invitation.last_name LIKE' => "{$_GET['search']}%", 'Invitation.request_token LIKE' => "{$_GET['search']}%", 'Invitation.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array("Invitation.invited_by" => $id);
        }
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'Invitation.id DESC'
        );
        $data = $this->paginate('Invitation');
        $this->set(compact('data'));
        $_requests = ($this->Session->check('Requests')) ? $this->Session->read('Requests') : array();
        $this->set('requests', $_requests);
    }

    public function admin_myteam() {
        $this->layout = 'admin_layout';
        $this->loadModel('User');
        $this->loadModel('AdminTeam');
        $this->loadModel('AdminReleaseRequest');
        $this->set('title_for_layout', "My Team");
        $currentUserSession = $this->Session->read('User');

        if ($currentUserSession['User']['is_trusted_admin'] == 0) {
            $this->Session->setFlash('You are not authorize to access this page.', 'default', 'info');
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }

        $id = $currentUserSession['User']['id'];
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('AdminTeam.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('AdminTeam.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1);
        }
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'User.id DESC',
        );

        $my_currect_date = date("Y-m-d H:i:s");
        $myreq = $this->AdminReleaseRequest->find("all", array("conditions" => array("AdminReleaseRequest.admin_id" => $id, "is_request_accepted" => 1, "'$my_currect_date' < " => "DATE_ADD(AdminReleaseRequest.approved_date, INTERVAL AdminReleaseRequest.days_admin_approved DAY) ")));

        $list_of_req_users = array();
        foreach ($myreq as $val) {
            $list_of_req_users[$val["AdminReleaseRequest"]["user_id"]] = array("c_user" => $val["AdminReleaseRequest"]["current_tl_id"], "req_user" => $val["AdminReleaseRequest"]["admin_id"], "id" => $val["AdminReleaseRequest"]["id"]);
        }


        $data = $this->paginate('AdminTeam');
        $this->set(compact('data', 'list_of_req_users'));
    }

    public function admin_listregularadmins() {
        $this->layout = 'admin_layout';
        $this->loadModel('User');
        $this->loadModel('AdminTeam');
        $this->loadModel('AdminReleaseRequest');
        $this->set('title_for_layout', "Add Team Member");
        $currentUserSession = $this->Session->read('User');

        if ($currentUserSession['User']['is_trusted_admin'] == 0) {
            $this->Session->setFlash('You are not authorize to access this page.', 'default', 'info');
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }

        $id = $currentUserSession['User']['id'];

        $currentTeamMembers = $this->AdminTeam->find(
            'list', array(
            'fields' => array('AdminTeam.user_id'),
            'conditions' => array('AdminTeam.admin_id' => $id)
            )
        );
        
        $my_data = $this->AdminTeam->find("list", 
                array("joins" => 
                    array(
                        array(
                            "table" => "admin_release_requests", 
                            "alias" => "AdminReleaseRequest", 
                            "type" => "INNER", 
                            "conditions" => array(
                                "AdminTeam.user_id=AdminReleaseRequest.user_id", 
                                "AdminTeam.admin_id=AdminReleaseRequest.admin_id"
                            )
                        )
                    ), 
                    "fields" => "AdminTeam.user_id",
                    'conditions' => array("OR" => array(
                            "AdminTeam.admin_id" => $id,
                            "AdminReleaseRequest.current_tl_id " => $id
                        )
                    )
                )
            );
        
        
        $my_data = array_merge($currentTeamMembers,$my_data);
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1, "NOT" => array("User.id" => array_values($my_data)), 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1, "NOT" => array("User.id" => array_values($my_data)));
        }
        
        $this->User->bindModel(
            array('hasOne' => array('AdminTeam'))
        );

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'User.id DESC'
        );

        $data = $this->paginate('User');
        
        $this->set(compact('data'));
    }

    public function addmyteam() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $currentUserSession = $this->Session->read('User');
        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                $this->loadModel('AdminTeam');
                $data = array();
                $this->AdminTeam->id = null;
                $data['AdminTeam']['admin_id'] = $currentUserSession['User']['id'];
                $data['AdminTeam']['user_id'] = base64_decode($this->request->data['userId']);
                if ($this->AdminTeam->save($data['AdminTeam'])) {

                    $this->loadModel("UserNotification");
                    $sub = sprintf("You have been added under %s %s", $currentUserSession['User']['first_name'], $currentUserSession['User']['last_name']);
                    $msg_ticket = sprintf('You have been added in the "%s %s" team.', $currentUserSession['User']['first_name'], $currentUserSession['User']['last_name']);

                    $user_noti = array(
                        0 => array("Notification" => array(
                                "subject" => $sub,
                                "body" => $msg_ticket,
                                "notification_type_id" => 0
                            ),
                            "UserNotification" => array(
                                "sender_id" => $currentUserSession['User']['id'],
                                "receiver_id" => base64_decode($this->request->data['userId'])
                            )
                        )
                    );

                    foreach ($user_noti as $val) {
                        $this->UserNotification->saveAll($val);
                    }


                    $validate['resMgs'] = true;
                } else {
                    $validate['resMgs'] = false;
                }
                echo json_encode($validate);
            }
        }
    }

    public function admin_addinvite() {
        $this->autoRender = false;
        $error = false;
        $this->loadModel('Invitation');
        $this->set('title_for_layout', "Add Inivitation");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $adminType = $currentUserSession['User']['is_trusted_admin'];
        if ($this->request->is('post')) {
            $this->Invitation->set($this->request->data);
            if ($this->Invitation->validates()) {
                $this->request->data['Invitation']['request_token'] = $this->generateRandomString(12);
                $this->Invitation->create();
                $this->request->data['Invitation']['invited_by'] = $id;
                if ($adminType == 1) {
                    $this->request->data['Invitation']['is_superadmin_approved'] = 1;
                } else {
                    $this->request->data['Invitation']['is_superadmin_approved'] = 0;
                }

                //CODE TO FIRE AN EMAIL END
                if ($this->Invitation->save($this->request->data['Invitation'], false)) {
                    if ($adminType == 1) {
                        //CODE TO FIRE AN EMAIL
                        $this->loadModel('EmailTemplate');
                        $signup_link = "<a href=" . Configure::read('SIGNUP_URL.URL') . '/' . $this->request->data['Invitation']['request_token'] . ">SIGN UP NOW!</a>";

                        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 3)));
                        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#CLICKHERETOSIGNUPNOW', '#CLICKHERETOSIGNUPNOW'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($this->request->data['Invitation']['first_name']), $currentUserSession['User']['first_name'] . ' ' . $currentUserSession['User']['last_name'], $signup_link), $temp['EmailTemplate']['mail_body']);
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
                    } else {
                        $this->Session->setFlash('Invitation has been added successfully. Superadmin will review the information and will approve and reject the inviation request.', 'default', 'success');
                    }
                    $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                } else {
                    $error = true;
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
                $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
            }
        }
    }

    public function admin_deleteteamuser($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('AdminTeam');
        $this->AdminTeam->id = $id;
        if ($this->AdminTeam->delete()) {
            $this->Session->setFlash('Team member has been removed successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Team member has been removed', 'default', 'error');
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'myteam', 'admin' => true));
    }

    public function admin_deleteinvitation($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('AdminTeam');
        $this->AdminTeam->id = $id;
        if ($this->AdminTeam->delete()) {
            $this->Session->setFlash('Team member has been removed successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Team member has been removed', 'default', 'error');
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'myteam', 'admin' => true));
    }

    public function admin_releaseteamuser($user_id = null, $admin_id = null, $c_user = null, $del_id = null) {
        $this->autoRender = false;
        $user_id = base64_decode($user_id);
        $admin_id = base64_decode($admin_id);
        $c_id = base64_decode($c_user);
        $del_id = base64_decode($del_id);
        $this->loadModel("AdminTeam");
        
        $P_detail = $this->AdminTeam->find("first", array("conditions" => array('AdminTeam.admin_id' => $c_id, 'AdminTeam.user_id' => $user_id)));
        $this->AdminTeam->updateAll(
            array('AdminTeam.admin_id' => "'$admin_id'"), array('AdminTeam.admin_id' => $c_id, 'AdminTeam.user_id' => $user_id)
        );
        $u_deatil = $this->AdminTeam->find("first", array("conditions" => array('AdminTeam.admin_id' => $admin_id, 'AdminTeam.user_id' => $user_id)));

        $sub = "Release team member";
        $msg_ticket = sprintf('"Hi %s %s" your team member "%s %s" has been release by "%s %s"', $u_deatil["TeamLeader"]["first_name"], $u_deatil["TeamLeader"]["last_name"], $u_deatil["User"]["first_name"], $u_deatil["User"]["last_name"], $P_detail["TeamLeader"]["first_name"], $P_detail["TeamLeader"]["last_name"]);
        $msg_ticket_user = sprintf('"Hi %s %s" you have been release by "%s %s"', $u_deatil["User"]["first_name"], $u_deatil["User"]["last_name"], $P_detail["TeamLeader"]["first_name"], $P_detail["TeamLeader"]["last_name"]);

        $this->loadModel("UserNotification");
        $user_noti = array(
            0 => array("Notification" => array(
                    "subject" => $sub,
                    "body" => $msg_ticket,
                    "notification_type_id" => 0
                ),
                "UserNotification" => array(
                    "sender_id" => $c_id,
                    "receiver_id" => $admin_id
                )
            ),
            1 => array("Notification" => array(
                    "subject" => $sub,
                    "body" => $msg_ticket_user,
                    "notification_type_id" => 0
                ),
                "UserNotification" => array(
                    "sender_id" => $c_id,
                    "receiver_id" => $user_id
                )
            )
        );

        foreach ($user_noti as $val) {
            $this->UserNotification->saveAll($val);
        }
        $this->loadModel('AdminReleaseRequest');


        if ($this->AdminReleaseRequest->delete($del_id)) {
            $this->Session->setFlash('Member has been released successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Memeber is not released', 'default', 'error');
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'myteam', 'admin' => true));
        
    }

    public function admin_resendinvitation($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Invitation->id = $id;

        $currentUserSession = $this->Session->read('User');
        $adminType = $currentUserSession['User']['is_trusted_admin'];

        $data = $this->Invitation->find('first', array('fields' => array(), 'conditions' => array('Invitation.id' => $id)));
        $invite_by = $this->User->find('first', array('fields' => array('User.first_name', 'User.last_name'), 'conditions' => array('User.id' => $data['Invitation']['invited_by'])));

        //CODE TO FIRE AN EMAIL
        $this->loadModel('EmailTemplate');
        $signup_link = "<a href=" . Configure::read('SIGNUP_URL.URL') . '/' . $data['Invitation']['request_token'] . ">SIGN UP NOW!</a>";

        $temp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.id' => 3)));
        $temp['EmailTemplate']['mail_body'] = str_replace(array('../../..', '#FIRSTNAME', '#ADMINFIRSTLASTNAME', '#CLICKHERETOSIGNUPNOW'), array(Configure::read('FULL_BASE_URL.URL'), ucwords($data['Invitation']['first_name']), $invite_by['User']['first_name'] . ' ' . $invite_by['User']['last_name'], $signup_link), $temp['EmailTemplate']['mail_body']);
        $this->set('template', $temp['EmailTemplate']['mail_body']);
        if ($data['Invitation']['email'] != '') {
            if ($adminType == 1) {
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
                $this->Session->setFlash('Invitation has been resend successfully. Superadmin will review the invitation request and then will approve and reject invitation request.', 'default', 'success');
            }
        } else {
            $this->Session->setFlash('Invitation has not been resend', 'default', 'error');
        }
        $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
    }

    /*     * **************CLIENT(USER) MODULE UNDER SUPER ADMIN PANEL*************************** */
    /*     * ************************LIST CLIENT(USER),CREATE NEW CLIENT,EDIT,ACTIAVTE***************** */

    public function ajax_set_favorite() {
        $this->layout = 'ajax';
        $this->loadModel('AdminUser');
        $adminuser_id = $this->request->data['adminuser_id'];
        $status = (int) $this->request->data['status'];
        $this->AdminUser->id = $adminuser_id;
        $data['AdminUser']['is_favorite'] = $status;
        if ($this->AdminUser->saveField('is_favorite', $data['AdminUser']['is_favorite'])) {
            $successMessage = $status == 1 ? 'User Has Been Added To Your Favorite List' : 'Checklist Has Been Removed From Your Favorite List';
            $this->set('result', array('status' => 'success', 'code' => '200', 'data' => array('message' => $successMessage)));
        } else {
            $this->set('result', array('status' => 'fail', 'code' => '201', 'data' => array('message' => 'Server Error : Could not verified')));
        }
        $this->set('_serialize', array('result'));
    }

    public function admin_listclients($favorites = null) {
        $this->loadModel('User');
        $this->loadModel('AdminUser');
        $this->layout = 'admin_layout';
        $this->set('title_for_layout', "My Clients");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        if (isset($favorites) && $favorites != '') {
            if ($this->request->isGet() && isset($_GET['search'])) {
                $condition = array('AdminUser.is_favorite' => 1, 'AdminUser.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
            } else {
                $condition = array('AdminUser.is_favorite' => 1, 'AdminUser.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1);
            }
        } else {
            if ($this->request->isGet() && isset($_GET['search'])) {
                $condition = array('AdminUser.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1, 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
            } else {
                $condition = array('AdminUser.admin_id' => $id, 'User.user_type_id' => Configure::read('UserType.user'), 'User.is_deleted !=' => 1);
            }
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'User.id DESC',
        );
        $data = $this->paginate('AdminUser');
        $this->set(compact('data'));
        $this->set('favorites', $favorites);
    }

    public function admin_addclient() {
        $this->autoRender = false;
        $error = false;
        $this->loadModel('User');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $adminType = $currentUserSession['User']['is_trusted_admin'];
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->request->data['User']['user_type_id'] = Configure::read('UserType.user');
                $this->request->data['User']['entry_ts'] = date('Y-m-d H:i:s');
                if ($adminType == 1) {
                    $this->request->data['User']['is_approved_client'] = 1;
                } else {
                    $this->request->data['User']['is_approved_client'] = 0;
                }
                $this->User->create();
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
                            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'admin' => true));
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
                    #save data in admin_users table
                    $this->loadModel('AdminUser');
                    $data['AdminUser']['user_id'] = $this->User->getLastInsertId();
                    $data['AdminUser']['admin_id'] = $id;
                    if ($this->AdminUser->save($data['AdminUser'], false)) {
                        if ($adminType == 1) {
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
                            $this->Session->setFlash('Client(User) has been added successfully and email sent at registed email address.', 'default', 'success');
                        } else {
                            $this->Session->setFlash('Client(User) has been added successfully and waiting for superadmin approval', 'default', 'success');
                        }
                        $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
                    } else {
                        $this->Session->setFlash('Client(User) adding request not completed. Try again!', 'message');
                        $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
                    }
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
                $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
            }
        }
    }

    public function admin_editclient($id = null) {
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
                            $this->redirect(array('controller' => 'users', 'action' => 'listclients', 'admin' => true));
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
                    $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
                    exit;
                }
            } else {

                $str = '';
                foreach ($this->User->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;

                $this->Session->setFlash('Client(User) is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
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

    public function admin_blockclient($id = null, $blocktype = null) {
        $id = base64_decode($id);
        $this->layout = 'admin_layout';
        $this->set('title_for_layout', 'Block Unblock Client');

        if (isset($blocktype) && $blocktype == 'block') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 1);

            $this->Session->setFlash('Client(User) has been blocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
        }
        if (isset($blocktype) && $blocktype == 'unblock') {
            $this->User->id = $id;
            $this->User->saveField('is_blocked', 0);
            $this->Session->setFlash('Client(User) has been unblocked successfully', 'default', 'success');
            $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
        }
    }

    public function admin_deleteclient($id = null) {
        $id = base64_decode($id);
        $this->layout = 'admin_layout';
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
        $this->redirect(array('controller' => 'admins', 'action' => 'listclients', 'admin' => true));
    }

    public function checkUserTeam() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                $this->loadModel('AdminTeam');
                $this->loadModel('AdminReleaseRequest');
                $userData = $this->AdminTeam->find('first', array(
                    'conditions' => array(
                        'AdminTeam.user_id' => base64_decode($this->request->data['userId'])
                    )
                ));
                if (!empty($userData)) {

                    $validate['userData'] = $userData;
                } else {
                    $validate['userData'] = false;
                }
                echo json_encode($validate);
            }
        }
    }

    public function updated_team_list() {
        $this->layout = 'ajax';
        $this->loadModel('User');
        $this->loadModel('AdminTeam');
        $this->set('title_for_layout', "Add Team Member");
        $currentUserSession = $this->Session->read('User');

        if ($currentUserSession['User']['is_trusted_admin'] == 0) {
            $this->Session->setFlash('You are not authorize to access this page.', 'default', 'info');
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }

        $id = $currentUserSession['User']['id'];

        $currentTeamMembers = $this->AdminTeam->find(
            'list', array(
            'fields' => array('AdminTeam.user_id'),
            'conditions' => array('AdminTeam.admin_id' => $id)
            )
        );

        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1, "NOT" => array("User.id" => array_values($currentTeamMembers)), 'OR' => array('User.user_name LIKE' => "{$_GET['search']}%", 'User.first_name LIKE' => "{$_GET['search']}%", 'User.last_name LIKE' => "{$_GET['search']}%", 'User.email LIKE' => "{$_GET['search']}%"));
        } else {
            $condition = array('User.user_type_id' => Configure::read('UserType.admin'), 'User.is_trusted_admin' => 0, 'User.is_deleted !=' => 1, "NOT" => array("User.id" => array_values($currentTeamMembers)));
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'User.id DESC',
        );
        $data = $this->paginate('User');
        $this->set(compact('data'));
    }

    public function admin_release_request() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->loadModel('AdminReleaseRequest');
        $currentUserSession = $this->Session->read('User');
        if ($this->request->is('post')) {
            $this->AdminReleaseRequest->create();
            $data['AdminReleaseRequest']['user_id'] = $this->request->data['userId'];
            $data['AdminReleaseRequest']['admin_id'] = $currentUserSession['User']['id'];
            $data['AdminReleaseRequest']['current_tl_id'] = $this->request->data['currentTeamLeadId'];
            $data['AdminReleaseRequest']['days_required'] = ((isset($this->request->data['daysRequired']) && !empty($this->request->data['daysRequired'])) ? $this->request->data['daysRequired'] : 10);
            $data['AdminReleaseRequest']['message_body'] = $this->request->data['messageBody'];
            if ($this->AdminReleaseRequest->save($data['AdminReleaseRequest'], false)) {
                $validate['resMgs'] = true;
            } else {
                $validate['resMgs'] = false;
            }

            echo json_encode($validate);
            exit;
        }
    }

    public function acceptRejectReleaseRequest() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->loadModel('AdminReleaseRequest');
        $this->loadModel('AdminTeam');
        $currentUserSession = $this->Session->read('User');
        if ($this->request->is('post')) {
            $this->AdminReleaseRequest->id = $this->request->data['releaseRequestId'];
            $requestData = $this->AdminReleaseRequest->read();
            //pr($requestData);
            if (
                $this->AdminReleaseRequest->saveField('is_request_accepted', $this->request->data['requestStatus'], array('validate' => false)) && 
                $this->AdminReleaseRequest->saveField('days_admin_approved', $this->request->data['requestApprovedValue'], array('validate' => false)) && 
                $this->AdminReleaseRequest->saveField('approved_date', date("Y-m-d H:i:s"), array('validate' => false))
            ) {
                $_adminTeamRecord = $this->AdminTeam->find('first', array('conditions' => array('AdminTeam.admin_id' => $requestData['AdminReleaseRequest']['current_tl_id'], 'AdminTeam.user_id' => $requestData['AdminReleaseRequest']['user_id'])));
                if ($this->request->data['requestStatus'] == 1 && isset($_adminTeamRecord) && !empty($_adminTeamRecord)) {
                    $_adminTeamRecord['AdminTeam']['admin_id'] = $requestData['AdminReleaseRequest']['admin_id'];
                    $this->AdminTeam->save($_adminTeamRecord);
                }
                $this->loadModel('Notification');
                $this->loadModel('UserNotification');
                if ($this->request->data['requestStatus'] == 1) {
                    $requestStatus = "Accepted";
                    $subject = "Release Request Accepted";
                    $notitype = 17;
                    $clientsubject = "New team admin assigned";
                    $clientbody = "Hello " . ($requestData['User']['first_name'] . " " . $requestData['User']['last_name']) . ", you have been assigned to new team for ".$this->request->data['requestApprovedValue']. " days.";
                }
                if ($this->request->data['requestStatus'] == 2) {
                    $requestStatus = "Rejected";
                    $subject = "Release Request Rejected";
                    $notitype = 18;
                    $this->AdminReleaseRequest->id = $this->request->data['releaseRequestId'];
                    $this->AdminReleaseRequest->saveField('rejected_comment', $this->request->data['reason'], array('validate' => false));
                }
                $body = "Hello " . ($requestData['Admin']['first_name'] . " " . $requestData['Admin']['last_name']) . ", your release request for " . ($requestData['User']['first_name'] . " " . $requestData['User']['last_name']) . " has been " . $requestStatus . " by " . ($requestData['TeamLeader']['first_name'] . " " . $requestData['TeamLeader']['last_name']);
                
                $user_noti = array(
                    0 => array(
                        "Notification" => array(
                            "subject" => $subject,
                            "body" => $body,
                            "notification_type_id" => $notitype
                        ),
                        "UserNotification" => array(
                            "sender_id" => $this->Session->read('User.User.id'),
                            "receiver_id" => $requestData['Admin']['id']
                        )
                    )
                );
                if ($this->request->data['requestStatus'] == 1) {
                    $user_noti[1]['Notification'] = array(
                        "subject" => $clientsubject,
                        "body" => $clientbody,
                        "notification_type_id" => 19
                    );
                    $user_noti[1]['UserNotification'] = array(
                        "sender_id" => $this->Session->read('User.User.id'),
                        "receiver_id" => $requestData['AdminReleaseRequest']['user_id']
                    );
                }
                foreach($user_noti as $val){
                    $this->UserNotification->saveAll($val);
                }
                
                if ($this->request->data['requestStatus'] == 2) {
                    $this->loadModel('Message');
                    $this->loadModel('UserMessage');
                
                    $user_msg = array(
                        "Message" => array(
                            "subject" => ($requestData['User']['first_name'] . " " . $requestData['User']['last_name']) . ' release request rejected',
                            "body" => $this->request->data['reason']
                        ),
                    );
                    
                    $savedMessage = $this->Message->save($user_msg);
                    if (!empty($savedMessage)) {
                        $userMessageData['UserMessage']['message_id'] = $this->Message->id;
                        $userMessageData['UserMessage']['parent_message_id'] = $this->Message->id;
                        $userMessageData['UserMessage']['sender_id'] = $this->Session->read('User.User.id');
                        $userMessageData['UserMessage']['receiver_id'] = $requestData['Admin']['id'];
                        $sucessId = $this->Message->UserMessage->save($userMessageData);
                    }
                }
            }
        }
    }

    /**
     * 
     */
    public function dont_show_for_this_session() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        //$this->loadModel('AdminReleaseRequest');
        $requests = ($this->Session->check('Requests')) ? $this->Session->read('Requests') : array();
        if ($this->request->is('post')) {
            $rrid = isset($this->request->data['releaseRequestId']) && trim($this->request->data['releaseRequestId']) != '' && is_numeric($this->request->data['releaseRequestId']) ? $this->request->data['releaseRequestId'] : 0;
            if ($rrid && !in_array($rrid, $requests)) {
                $requests[] = $rrid;
                $this->Session->write('Requests', $requests);
            }
        }
        echo json_encode($requests);
        //echo json_encode($rrid);
        exit;
    }

    function come_back_myuser() {
        $this->autoRender = false;
        $this->loadModel("AdminReleaseRequest");
        $this->loadModel("AdminTeam");
        $my_currect_date = date("Y-m-d H:i:s");
        $sql = "select rr.id, rr.admin_id,rr.user_id,rr.current_tl_id  from admin_release_requests as rr 
			inner join admin_teams as at on at.admin_id=rr.admin_id
			where rr.is_request_accepted=1  and '$my_currect_date' > DATE_ADD(rr.approved_date, INTERVAL rr.days_admin_approved DAY) 
			";
        $all_d = $this->AdminReleaseRequest->query($sql);


        if (!empty($all_d)) {
            foreach ($all_d as $k => $v) {


                $up = $this->AdminTeam->updateAll(
                    array('AdminTeam.admin_id' => $v["rr"]["current_tl_id"]), array(
                    'AdminTeam.admin_id' => $v["rr"]["admin_id"],
                    'AdminTeam.user_id' => $v["rr"]["user_id"]
                    )
                );
                if ($up) {
                    $this->AdminReleaseRequest->delete($v["rr"]["id"]);
                }
            }
        }

        //$all_request=$this->AdminReleaseRequest->find("all",array("conditions"=>array("is_request_accepted"=>1)));
    }

}
