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

class NotificationsController extends AppController {

    public $name = 'Notifications';
    public $uses = array('Notification', 'UserNotification', 'User', 'EmailTemplate');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function listnotifications() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', "Notifications");
        $condition = array(
            'UserNotification.receiver_id' => $this->Session->read('User.User.id'),
            'UserNotification.is_receiver_deleted <> ' => 1
        );
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.User'),
            'order' => 'UserNotification.id DESC'
        );
        $data = $this->paginate('UserNotification');
        $this->set(compact('data'));
    }

    public function delete_notification($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->UserNotification->id = $id;
        if ($this->UserNotification->exists()) {
            $this->UserNotification->id = $id;
            $this->UserNotification->saveField('is_receiver_deleted', 1);
            $this->Session->setFlash('Notification has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Notification is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications'));
    }

    public function delete_allnotification() {
        $this->autoRender = false;
        $notifications = $this->UserNotification->find('all', array(
            'fields' => array('UserNotification.id'),
            'conditions' => array(
                'UserNotification.receiver_id =' => $this->Session->read('User.User.id'),
            ),
            'order' => array('UserNotification.id ASC')
        ));
        foreach ($notifications as $key => $notification) {
            $this->UserNotification->id = (int) $notification['UserNotification']['id'];
            $this->UserNotification->saveField('is_receiver_deleted', 1);
        }
        $this->Session->setFlash('All Notification(s) has been deleted successfully', 'default', 'success');
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications'));
    }

    public function view_notification($id = null) {
        // $this->autoRender = false;
        $PopupTitle = "Notification Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $notification = $this->UserNotification->find('first', array(
                'conditions' => array(
                    'UserNotification.notification_id' => $id,
                    'UserNotification.receiver_id' => $this->Session->read('User.User.id')
                )
            ));
            if (!empty($notification) && $notification['UserNotification']['is_read'] != 1) {
                $this->UserNotification->id = (int) $notification['UserNotification']['id'];
                $this->UserNotification->saveField('is_read', 1);
                $this->UserNotification->saveField('read_datetime', date('Y-m-d H:i:s'));
            }

            $this->set('notificationData', $notification);
            $this->set('_serialize', array('notificationData', 'PopupTitle'));
        }
    }

    public function admin_view_notification($id = null) {
        // $this->autoRender = false;
        $PopupTitle = "Notification Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $notification = $this->UserNotification->find('first', array(
                'conditions' => array(
                    'UserNotification.notification_id' => $id,
                    'UserNotification.receiver_id' => $this->Session->read('User.User.id')
                )
            ));
            if (!empty($notification) && $notification['UserNotification']['is_read'] != 1) {
                $this->UserNotification->id = (int) $notification['UserNotification']['id'];
                $this->UserNotification->saveField('is_read', 1);
                $this->UserNotification->saveField('read_datetime', date('Y-m-d H:i:s'));
            }

            $this->set('notificationData', $notification);
            $this->set('_serialize', array('notificationData', 'PopupTitle'));
        }
    }

    public function superadmin_view_notification($id = null) {
        // $this->autoRender = false;
        $PopupTitle = "Notification Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $notification = $this->UserNotification->find('first', array(
                'conditions' => array(
                    'UserNotification.notification_id' => $id
                )
            ));
            if (!empty($notification) && $notification['UserNotification']['is_read'] != 1) {
                $this->UserNotification->id = (int) $notification['UserNotification']['id'];
                $this->UserNotification->saveField('is_read', 1);
                $this->UserNotification->saveField('read_datetime', date('Y-m-d H:i:s'));
            }

            $this->set('notificationData', $notification);
            $this->set('_serialize', array('notificationData', 'PopupTitle'));
        }
    }

    public function superadmin_listnotifications() {
        $this->loadModel('User');
        $this->layout = 'superadmin_layout';
        $condition = array(
            'Notification.is_deleted' => 0,
            // 'UserNotification.receiver_id' => $this->Session->read('User.User.id')
        );
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'Notification.id DESC',
            'recursive' => 2
        );
        $data = $this->paginate('Notification');

        $this->set(compact('data'));

        $this->loadModel('User');
        $user_res = $this->User->find('list', array('conditions' => array('User.is_deleted' => 0, 'User.is_blocked' => 0), 'fields' => array('User.id', 'User.full_name'), 'order' => array('User.first_name ASC'), 'recursive' => -1));
        $this->set('user_res', $user_res);

        $str = '';

        foreach ($user_res as $key => $val):

            $str.="{value: $key, text: '$val'},";
        endforeach;
        $this->set('users', trim($str));
    }

    /* public function admin_listnotifications() {
      $this->loadModel('User');
      $this->layout = 'admin_layout';
      $condition = array(
      'Notification.is_deleted' => 0,
      // 'UserNotification.receiver_id' => $this->Session->read('User.User.id')
      );

      $this->paginate = array(
      'conditions' => $condition,
      'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
      'order' => 'Notification.id DESC',
      'recursive' => 2
      );
      $data = $this->paginate('Notification');

      $this->set(compact('data'));

      $this->loadModel('User');
      $user_res = $this->User->find('list', array('conditions' => array('User.is_deleted' => 0, 'User.is_blocked' => 0), 'fields' => array('User.id', 'User.full_name'), 'order' => array('User.first_name ASC'), 'recursive' => -1));
      $this->set('user_res', $user_res);

      $str = '';

      foreach ($user_res as $key => $val):

      $str.="{value: $key, text: '$val'},";
      endforeach;
      $this->set('users', trim($str));
      } */

    public function admin_listnotifications() {
        $this->layout = 'admin_layout';
        $this->set('title_for_layout', "Notifications");
        $condition = array(
            'UserNotification.receiver_id' => $this->Session->read('User.User.id'),
            'UserNotification.is_receiver_deleted <> ' => 1
        );
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'UserNotification.id DESC'
        );
        $data = $this->paginate('UserNotification');
        $this->set(compact('data'));
    }

    public function addusers() {  //notification users
        $this->autoRender = false;
        $currentUserSession = $this->Session->read('User');
        $this->layout = 'ajax';
        $this->loadModel('UserNotification');
        if (!empty($this->request->data['value'])) {
            //delete old records and fresh insert new one
            $this->UserNotification->deleteAll(array('notification_id' => $this->request->data['pk']), false);
            foreach ($this->request->data['value'] as $user_id):
                $this->UserNotification->id = null;
                $data['UserNotification']['sender_id'] = $currentUserSession['User']['id'];
                $data['UserNotification']['receiver_id'] = $user_id;
                $data['UserNotification']['notification_id'] = $this->request->data['pk'];
                $this->UserNotification->save($data['UserNotification']);
            endforeach;
        }
    }

    public function superadmin_addnotification() {
        $this->autoRender = false;
        $error = false;
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->loadModel('Notification');
        if ($this->request->is('post')) {
            $this->Notification->set($this->request->data);
            if ($this->Notification->validates()) {
                $this->Notification->create();
                if ($this->Notification->save($this->request->data['Notification'], false)) {
                    $this->Session->setFlash('Notification has been added successfully.Now select the users from listing', 'default', 'success');
                    $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->Notification->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Notification adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true));
            }
        }
    }

    public function admin_addnotification() {
        $this->autoRender = false;
        $error = false;
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->loadModel('Notification');
        if ($this->request->is('post')) {
            $this->Notification->set($this->request->data);
            if ($this->Notification->validates()) {
                $this->Notification->create();
                if ($this->Notification->save($this->request->data['Notification'], false)) {
                    $this->Session->setFlash('Notification has been added successfully.Now select the users from listing', 'default', 'success');
                    $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'admin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->Notification->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Notification adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'admin' => true));
            }
        }
    }

    public function admin_deletenotification($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Notification->id = $id;
        if ($this->Notification->delete($id, true)) {
            $this->Session->setFlash('Notification has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Notification is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'admin' => true));
    }

    public function superadmin_deletenotification($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Invitation');
        $this->Notification->id = $id;
        if ($this->Notification->delete($id, true)) {
            $this->Session->setFlash('Notification has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Notification is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true));
    }

    /* public function read_notification() {
      $currentUserSession = $this->Session->read('User');
      $id = $currentUserSession['User']['id'];
      $this->layout = 'login_layout';
      $this->loadModel('UserNotification');
      $this->set('title_for_layout', 'Read Notification');
      if ($this->request->is('post')) {
      $this->UserNotification->unbindModel(array('belongsTo' => array('Notification', 'Sender', 'Receiver')));
      $current_datetime = date('Y-m-d h:i:s');
      $this->UserNotification->updateAll(
      array('UserNotification.is_read' => 1, 'UserNotification.read_datetime' => "'" . $current_datetime . "'"), array('UserNotification.receiver_id' => $id), false
      );
      switch ($currentUserSession['User']['user_type_id']) {
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
      } */

    public function readNotification() {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $id = $this->request->data['notyId'];
            $this->UserNotification->id = (int) $id;
            if ($this->UserNotification->exists()) {
                $this->UserNotification->saveField('is_read', 1);
                $this->UserNotification->saveField('read_datetime', date('Y-m-d H:i:s'));
            }
        }
    }

    public function dismissNotification() {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $id = $this->request->data['notyId'];
            $this->UserNotification->id = (int) $id;
            if ($this->UserNotification->exists()) {
                $this->UserNotification->saveField('is_current_dismiss', 1);
            }
        }
    }

    public function latestNotifications() {
        $timeStart = time();
        $this->layout = 'ajax';
        $this->autoRender = false;
        $userId = $this->Session->read('User.User.id');
        if ($this->request->is('post')) {
            if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
                $timestamp = $this->request->data['timestamp'];
            } else {
                // get current database time
                $nowTime = $this->Notification->getMySQLNowTimestamp();
                $timestamp = $nowTime[0][0]['timestamp'];
            }
        } else {
            $nowTime2 = $this->Notification->getMySQLNowTimestamp();
            $timestamp = $nowTime2[0][0]['timestamp'];
        }
        $newData = false;
        $notifications = array();

        // loop while there is no new data and is running for less than 20 seconds
        while (!$newData && (time() - $timeStart) < 30) {
            // check for new data
            $notificationsCount = $this->UserNotification->getNewNotificationsCount();
            // pr($this->Message->getLastQuery());
            if (isset($notificationsCount)) {
                $notifications[] = $notificationsCount;
                $newData = true;
            }
            // let the server rest for a while
            usleep(1000000);
        }
        // get current database time
        $nowTime3 = $this->Notification->getMySQLNowTimestamp();
        $timestamp = $nowTime3[0][0]['timestamp'];

        // output
        $data = array('notifications' => $notifications, 'timestamp' => $timestamp);
        echo json_encode($data);
        exit;
    }

    public function superadmin_send_push_notification() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->Notification->set($this->request->data);
            if ($this->Notification->validates()) {
                $this->loadModel('User');
                $sucessCounter = 0;
                foreach ($this->request->data['UserType'] as $userTypeId) {
                    $typeUsers = $this->User->fetchUserByTypeId($userTypeId);
                    if (!empty($typeUsers)) {
                        foreach ($typeUsers as $userData) {
                            $this->request->data['Notification']['notification_type_id'] = 1;
                            $savedNotification = $this->Notification->save($this->request->data);
                            if (!empty($savedNotification)) {
                                $sucessCounter++;
                                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                                $this->request->data['UserNotification']['receiver_id'] = $userData['User']['id'];
                                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
                            }
                        }
                    }
                }
                if ($sucessCounter > 0) {
                    $this->Session->setFlash($sucessCounter . ' Push Notification has been sent successfully', 'default', 'success');
                } else {
                    $this->Session->setFlash('Error found. Push Notification not sent successfully.', 'default', 'error');
                }
            } else {
                $str = '';
                foreach ($this->Notification->validationErrors as $error) {
                    $str .= $error[0] . '<br/>';
                }
                $this->Session->setFlash('Unable to send notification. <br/>' . $str, 'default', 'error');
            }
        }
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true));
    }

    public function admin_send_push_notification() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->Notification->set($this->request->data);
            if ($this->Notification->validates()) {
                $this->loadModel('User');
                $sucessCounter = 0;
                foreach ($this->request->data['UserType'] as $userTypeId) {
                    $typeUsers = $this->User->fetchUserByTypeId($userTypeId);
                    if (!empty($typeUsers)) {
                        foreach ($typeUsers as $userData) {
                            $this->request->data['Notification']['notification_type_id'] = 1;
                            $savedNotification = $this->Notification->save($this->request->data);
                            if (!empty($savedNotification)) {
                                $sucessCounter++;
                                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                                $this->request->data['UserNotification']['receiver_id'] = $userData['User']['id'];
                                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
                            }
                        }
                    }
                }
                if ($sucessCounter > 0) {
                    $this->Session->setFlash($sucessCounter . ' Push Notification has been sent successfully', 'default', 'success');
                } else {
                    $this->Session->setFlash('Error found. Push Notification not sent successfully.', 'default', 'error');
                }
            } else {
                $str = '';
                foreach ($this->Notification->validationErrors as $error) {
                    $str .= $error[0] . '<br/>';
                }
                $this->Session->setFlash('Unable to send notification. <br/>' . $str, 'default', 'error');
            }
        }
        $this->redirect(array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true));
    }

    public function admin_send_notification_to_user() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->Notification->set($this->request->data);
            if ($this->Notification->validates()) {
                $this->loadModel('User');
                $sucessCounter = 0;
                $this->request->data['Notification']['notification_type_id'] = 0;
                $savedNotification = $this->Notification->save($this->request->data);
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                $this->request->data['UserNotification']['receiver_id'] = $this->request->data['Notification']['user_id'];
                if ($this->Notification->UserNotification->saveAll($this->request->data['UserNotification'])) {
                    $this->Session->setFlash('Notification has been sent successfully', 'default', 'success');
                } else {
                    $this->Session->setFlash('Error found. Notification not sent successfully.', 'default', 'error');
                }
            }
        } else {
            $str = '';
            foreach ($this->Notification->validationErrors as $error) {
                $str .= $error[0] . '<br/>';
            }
            $this->Session->setFlash('Unable to send notification. <br/>' . $str, 'default', 'error');
        }

        $this->redirect(array('controller' => 'admins', 'action' => 'myteam', 'admin' => true));
    }

}
