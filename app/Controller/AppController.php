<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
//App::uses('ConnectionManager', 'Model');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $helpers = array('Html', 'Time', 'Number');
    public $components = array('RequestHandler', 'Session', 'DebugKit.Toolbar', 'Cookie');
    public $nonLoginActions = array('login', 'login_unlock', 'login_clear', 'signup', 'forgotpassword', 'admin_login', 'newdesign', 'goto_superadmindashboard', 'ajax_check_username', 'ajax_check_email', 'fetch_users', 'ajaxValidateUsername', 'ajaxValidateUsernamePassword', 'ajaxValidateUsernameEmail');

    public function beforeFilter() {
        $this->layout = 'login_layout';
        $action = $this->params['action'];
        $controller = $this->params['controller'];

        if (!in_array($this->params['action'], $this->nonLoginActions)) {
            $arrData = $this->_checkSession();
            $this->set('usersession', $arrData);
            if (!empty($arrData)) {
                // Set Session to display on header UserTypeID
                $this->set('Name', $arrData['User']['first_name'] . ' ' . $arrData['User']['last_name']);
                $this->set('firstName', $arrData['User']['first_name']);
                $this->set('UserTypeName', $arrData['UserType']['name']);
                $this->set('UserTypeID', $arrData['User']['user_type_id']);
            }
            $SUPER_ADMIN = $this->Session->read('SUPER_ADMIN');
            $this->set('SUPER_ADMIN', $SUPER_ADMIN);
        } elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'login') {
            if ($this->Cookie->check('UserData')) {
                $this->User->recursive = -1;
                $UserData = $this->User->findByUserName(
                    $this->Cookie->read('UserData.user_name'), array('user_name', 'first_name', 'last_name', 'business_name', 'display_name', 'full_name', 'profile_photo')
                );
                if (!empty($UserData)) {
                    $this->redirect('/users/login_unlock');
                }
            }
        }
        
        $this->fetchNotificationTypes();
    }

    public function beforeRender() {
        Cache::clear();
        if (!in_array($this->params['action'], $this->nonLoginActions)) {
            $isAdmin = !empty($this->request->params['admin']);
            $isSuperAdmin = !empty($this->request->params['superadmin']);
            $data = $this->Session->check('User');
            if (!$data) {
                $this->redirect('/users/login');
            } else {
                $currSessionData = $this->Session->read('User');
                if ($isAdmin == 1 && $currSessionData['User']['user_type_id'] != Configure::read('UserType.admin')) {
                    $this->redirect('/users/login');
                }
                if ($isSuperAdmin == 1 && $currSessionData['User']['user_type_id'] != Configure::read('UserType.superadmin')) {
                    $this->redirect('/users/login');
                }
            }

            if ($currSessionData['User']['user_type_id'] == Configure::read('UserType.admin') || $currSessionData['User']['user_type_id'] == Configure::read('UserType.superadmin')) {
                $this->loadModel('Invitation');
                $unreadInvites = $this->Invitation->getUnreadInvites();
                $this->set('unreadInvites', $unreadInvites);

                $this->loadModel('User');
                $unreadCientInvites = $this->User->getUnreadClientsInvites();
                $this->set('unreadCientInvites', $unreadCientInvites);

                $this->loadModel('SupportTicket');
                $unreadTickets = $this->SupportTicket->getUnreadSupportTickets();
                $this->set('unreadTickets', $unreadTickets);

                $this->loadModel('AdminReleaseRequest');
                $releaseRequestData = $this->AdminReleaseRequest->find(
                    'all', array(
                    'conditions' => array(
                        'AdminReleaseRequest.current_tl_id' => $currSessionData['User']['id'],
                        'AdminReleaseRequest.is_request_accepted' => 0,
                        'AdminReleaseRequest.is_tl_deleted <> ' => 1
                    )
                    )
                );
                $this->set('releaseRequestData', $releaseRequestData);
            }

            if ($currSessionData['User']['user_type_id'] == Configure::read('UserType.user')) {
                $this->loadModel('UserTask');
                $inProgressTasks = $this->UserTask->getInProgressTasks();
                $this->set('inProgressTasks', $inProgressTasks);
            }

            $this->loadModel('UserMessage');
            $unreadMessages = $this->UserMessage->getUnreadMessages();
            $this->set('unreadMessages', $unreadMessages);

            $this->loadModel('UserNotification');
            $unreadNotifications = $this->UserNotification->getUnreadNotifications();
            $this->set('unreadNotifications', $unreadNotifications);
            $notify_data = $this->UserNotification->find(
                'all', array(
                'conditions' => array(
                    'UserNotification.receiver_id' => $currSessionData['User']['id'],
                    'UserNotification.is_read <> ' => 1,
                    'UserNotification.is_current_dismiss <> ' => 1,
                    'UserNotification.is_receiver_deleted <> ' => 1
                )
                )
            );
            $this->set('notify_data', $notify_data);
        }
    }

    /*
     * *********************************************************************
     * function : _checkUser
     * functionality : check user session
     * **********************************************************************
     */

    private function _checkSession() {
        $data = $this->Session->check('User');
        if (!$data) {
            $this->redirect('/users/login');
        } else {
            $currSessionData = $this->Session->read('User');
            $this->loadModel('User');
            $userdata = $this->User->find(
                'first', array(
                'conditions' => array(
                    'User.password' => $currSessionData['User']['password'],
                    'User.user_name' => $currSessionData['User']['user_name'],
                    'User.is_deleted <>' => 1
                )
                )
            );
            return $userdata;
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function stringToSlug($str) {
        // turn into slug
        $str = Inflector::slug($str);
        // to lowercase
        $str = strtolower($str);
        return $str;
    }
    
    public function fetchNotificationTypes() {
        $this->loadModel('NotificationType');
        $noti_types = $this->NotificationType->find('all');
        $notification_types = array('0' => 'New notification');
        if(!empty($noti_types)) {
            foreach($noti_types as $ntype) {
               $notification_types[$ntype['NotificationType']['id']] = $ntype['NotificationType']['name'];
            }
            Configure::write("notification_types", $notification_types);
        }
    }

}
