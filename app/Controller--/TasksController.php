<?php

/*
 * ***********************************************************************************
 * Tasks Controller
 * Functionality		 :	Tasks related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class TasksController extends AppController {

    public $name = 'Tasks';
    public $uses = array('Task', 'User', 'UserTask', 'TaskHistory');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function superadmin_listtasks() {

        $this->layout = 'superadmin_layout';
        $this->set('title_for_layout', "Tasks");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $condition = array();
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('Task.is_deleted <>' => 1, 'OR' => array('Task.name LIKE' => "%{$_GET['search']}%", 'Task.description LIKE' => "%{$_GET['search']}%"));
        } else {
            $condition = array('Task.is_deleted <>' => 1);
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Superadmin'),
            'order' => 'Task.id DESC',
        );
        $data = $this->paginate('Task');
        $this->set(compact('data'));
    }

    public function admin_listtasks() {

        $this->layout = 'admin_layout';
        $this->set('title_for_layout', "Tasks");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $condition = array();
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('UserTask.admin_id' => $id, 'UserTask.is_deleted <>' => 1, 'OR' => array('UserTask.title LIKE' => "%{$_GET['search']}%", 'UserTask.task_description LIKE' => "%{$_GET['search']}%"));
        } else {
            $condition = array('UserTask.admin_id' => $id, 'UserTask.is_deleted <>' => 1);
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Admin'),
            'order' => 'UserTask.id DESC',
        );
        $data = $this->paginate('UserTask');
        $this->set(compact('data'));
        $tasksList = $this->Task->find(
                'list', array(
            'fields' => array(
                'Task.id',
                'Task.name',
            )
                )
        );
        $this->set(compact('tasksList'));
    }

    public function listtasks() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', "Tasks");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $condition = array();
        if ($this->request->isGet() && isset($_GET['search'])) {
            $condition = array('UserTask.client_id' => $id, 'UserTask.is_deleted <>' => 1, 'OR' => array('UserTask.title LIKE' => "%{$_GET['search']}%", 'UserTask.task_description LIKE' => "%{$_GET['search']}%"));
        } else {
            $condition = array('UserTask.client_id' => $id, 'UserTask.is_deleted <>' => 1);
        }

        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.User'),
            'order' => 'UserTask.id DESC',
        );
        $data = $this->paginate('UserTask');
        $this->set(compact('data'));
    }

    public function view_task($id = null) {
        //$this->autoRender = false;
        $PopupTitle = "Task Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $task = $this->UserTask->find('first', array(
                'conditions' => array(
                    'UserTask.task_id' => $id,
                    'UserTask.client_id' => $this->Session->read('User.User.id')
                )
            ));
            
            
             $tastData = $this->TaskHistory->find("all", array('conditions' => array('TaskHistory.task_id' => $id), 'order' => 'TaskHistory.created asc'));
        if (!empty($tastData)) {
            foreach ($tastData as $k => &$val) {
                $tastData[$k] = $val;
            }
        }
        $this->set('tastData', $tastData);
            $this->set('taskData', $task);
            $this->set('_serialize', array('taskData','tastData', 'PopupTitle'));
        }
        //exit();
    }
    public function admin_view_task($id = null) {
        //$this->autoRender = false;
        $PopupTitle = "Task Details";
        $id = base64_decode($id);
		
		
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $taskData = $this->UserTask->find('first', array(
                'conditions' => array(
                    'UserTask.id' => $id
                )
            ));
            
             $tastData = $this->TaskHistory->find("all", array('conditions' => array('TaskHistory.task_id' => $id), 'order' => 'TaskHistory.created asc'));
            if (!empty($tastData)) {
                foreach ($tastData as $k => &$val) {
                    $tastData[$k] = $val;
                }
            }
            $this->set("taskData", $taskData);
            $this->set("tastData", $tastData);
            $this->set('_serialize', array('taskData','tastData', 'PopupTitle'));
        }
        //exit();
    }

    public function superadmin_addtask() {
        $this->autoRender = false;
        $error = false;
        $this->loadModel('Task');
        if ($this->request->is('post')) {
            $this->Task->set($this->request->data);
            if ($this->Task->validates()) {
                $this->Task->create();
                if ($this->Task->save($this->request->data['Task'], false)) {
                    if ($this->request->data['Task']['image']['name'] != '') {
                        $pictureTempName = $this->request->data['Task']['image']['tmp_name'];
                        $pictureName = $this->request->data['Task']['image']['name'];
                        $pictureType = $this->request->data['Task']['image']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Task icon not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'tasks_icons';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;
                            $logos = array('128' => $image);
                            $dimentions = array(128 => 128);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }
                            $this->Task->saveField('icon_image', $image, false);
                        }
                    }

                    if (isset($this->request->data['Task']['file']['name'])) {
                        $destination = str_replace('\\', '/', WWW_ROOT) . 'files/' . $this->request->data['Task']['file']['name'];
                        move_uploaded_file($this->data['Task']['file']['tmp_name'], $destination);
                        $this->Task->saveField('info5', $this->request->data['Task']['file']['name'], false);
                    }

                    $this->loadModel('Notification');
                    $this->loadModel('UserNotification');
                    $notificationData['Notification']['subject'] = "New Task Addition";
                    $notificationData['Notification']['body'] = "Hello User! A new OmniHustle task has been added to our system by popular demand. Explore our Tasks section for our newest offers, and schedule tasks with your Web Agent today!";
                    $notificationData['Notification']['is_pish_notification'] = 1;
                    $this->Notification->create();
                    if ($this->Notification->save($notificationData['Notification'], false)) {
                        $typeUsers = $this->User->fetchUserByTypeId(3);
                        if (!empty($typeUsers)) {
                            foreach ($typeUsers as $userData) {
                                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                                $this->request->data['UserNotification']['receiver_id'] = $userData['User']['id'];
                                $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
                            }
                        }
                        $adminUsers = $this->User->fetchUserByTypeId(2);
                        if (!empty($adminUsers)) {
                            foreach ($adminUsers as $userData) {
                                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                                $this->request->data['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                                $this->request->data['UserNotification']['receiver_id'] = $userData['User']['id'];
                                $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
                            }
                        }
                    }

                    $this->Session->setFlash('Task has been added successfully.', 'default', 'success');
                    $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->Task->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Task adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
            }
        }
    }

    public function admin_addusertask() {
        $currentUserSession = $this->Session->read('User');
        $adminId = $currentUserSession['User']['id'];
        $this->autoRender = false;
        $error = false;
        $this->loadModel('UserTask');
        if ($this->request->is('post')) {
            $this->loadModel('User');
            $clientName = str_replace("@" . Configure::read('SITE_EMAIL.Email'), "", $this->request->data['UserTask']['client_user_name']);
            $clientData = $this->User->findByUserName($clientName);
            if (!empty($clientData)) {
                $this->request->data['UserTask']['client_id'] = $clientData['User']['id'];
            }
            $this->request->data['UserTask']['admin_id'] = $adminId;
            $this->UserTask->set($this->request->data);
            if ($this->UserTask->validates()) {
                $this->UserTask->create();
                if ($this->UserTask->save($this->request->data['UserTask'], false)) {
                    $this->Session->setFlash('Task has been added successfully.', 'default', 'success');
                    $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error = true) {
                $errors = $this->UserTask->validationErrors;
                if (!empty($errors)) {
                    $str = '';
                    foreach ($errors as $key => $val):
                        $str.=$val[0];
                    endforeach;
                }
                $this->Session->setFlash('Task adding request not completed due to following errors : .' . $str . '. Try again!', 'message');
                $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
            }
        }
    }

    public function superadmin_edittask($id = null) {
        $currentUserSession = $this->Session->read('User');
        $PopupTitle = "Edit task";
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->Task->set($this->request->data);
            if ($this->Task->validates()) {
                $this->Task->id = $id;
                $currTask = $this->Task->read();
                if ($this->Task->save($this->request->data, false, array('name', 'price_type', 'price', 'description'))) {
                    if ($this->request->data['Task']['image']['name'] != '') {
                        $pictureTempName = $this->request->data['Task']['image']['tmp_name'];
                        $pictureName = $this->request->data['Task']['image']['name'];
                        $pictureType = $this->request->data['Task']['image']['type'];
                        $ext = explode('.', $pictureName);
                        $ext = end($ext);
                        if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                            $this->Session->setFlash('Task icon not uploaded. Please upload png/jpg/gif format only', 'default', 'message');
                            $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
                        } else {
                            $uploadFolder = 'tasks_icons';
                            App::import('Component', 'Resize');
                            $ResizeComp = new ResizeComponent();
                            $image = $this->generateRandomString(5) . '.' . $ext;
                            $logos = array('128' => $image);
                            $dimentions = array(128 => 128);
                            list( $width, $height, $sourceType ) = getimagesize($pictureTempName);

                            foreach ($dimentions as $picWidth => $picHeight) {
                                $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $logos[$picWidth];
                                $ResizeComp->resize_fill($pictureTempName, $destination, $picWidth, $picHeight);
                                /* if ($width <= $picWidth && $height <= $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                                  } else if ($width > $picWidth) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, 0, 0, 0, 0, 0, 0);
                                  } else if ($height > $picHeight) {
                                  $ResizeComp->resize($pictureTempName, $destination, 'height', 0, $picHeight, 0, 0, 0, 0);
                                  } */
                            }
                            if ($this->Task->saveField('icon_image', $image, false)) {
                                $oldImage = $currTask['Task']['icon_image'];
                                if ($oldImage != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage)) {
                                    unlink(str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $oldImage);
                                }
                            }
                        }
                    }
                    if (isset($this->request->data['Task']['file']['name'])) {
                        $destination = str_replace('\\', '/', WWW_ROOT) . 'files/' . $this->request->data['Task']['file']['name'];
                        move_uploaded_file($this->data['Task']['file']['tmp_name'], $destination);
                        if ($this->Task->saveField('info5', $this->request->data['Task']['file']['name'], false)) {
                            $oldFile = $currTask['Task']['info5'];
                            if ($oldFile != '' && file_exists(str_replace('\\', '/', WWW_ROOT) . 'files/' . $oldFile)) {
                                unlink(str_replace('\\', '/', WWW_ROOT) . 'files/' . $oldFile);
                            }
                        }
                    }
                    $this->Session->setFlash('Task has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
                    exit;
                }
            } else {
                $str = '';
                foreach ($this->Task->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;
                $this->Session->setFlash('Task is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
                exit;
            }
        } else {
            // $this->User->recursive = -1;
            $id = base64_decode($id);
            $this->Task->id = $id;
            $this->request->data = $this->Task->read();
            if ($this->RequestHandler->isAjax()) {
                $this->set('taskData', $this->request->data);
                $this->set('_serialize', array('taskData', 'PopupTitle'));
            }
        }
    }

    public function admin_edittask($id = null) {
        $currentUserSession = $this->Session->read('User');
        $this->loadModel('TaskHistory');
        $tastData = $this->TaskHistory->find("all", array('conditions' => array('TaskHistory.user_task_id' => base64_decode($id)), 'order' => 'TaskHistory.created asc'));
        if (!empty($tastData)) {
            foreach ($tastData as $k => &$val) {
                $tastData[$k] = $val;
            }
        }
        $this->set('tastData', $tastData);

        $PopupTitle = "Edit task";
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            $this->UserTask->set($this->request->data);
            if ($this->UserTask->validates()) {
                $this->UserTask->id = $id;
                $currTask = $this->UserTask->read();
                if ($this->request->data['UserTask']['status_completed'] == 100) {
                    $this->request->data['UserTask']['is_completed'] = 1;
                } else {
                    $this->request->data['UserTask']['is_completed'] = 0;
                }

                if ($this->UserTask->save($this->request->data, false, array('task_title', 'deadline_datetime', 'status_completed', 'task_description', 'is_completed'))) {
                    $id = $id;
                    $this->UserTask->id = $id;
                    $arr = array();
                    $arr = $this->UserTask->read();
                    $save_arr = array();
                    if (!empty($arr) && !empty($arr["UserTask"])) {
                        foreach ($arr["UserTask"] as $k => $val) {
                            if ($k == "id") {
                                $k = "user_task_id";
                            }
                            $save_arr[$k] = $val;
                        }
                    }
                    if ($this->TaskHistory->save($save_arr)) {
                        //if ($this->TaskDescription->save(array("task_description" => $this->request->data["UserTask"]["task_description"], "task_id" => $id))) {
                        if ($this->request->data['UserTask']['status_completed'] == 25 || $this->request->data['UserTask']['status_completed'] == 50 || $this->request->data['UserTask']['status_completed'] == 100) {
                            $this->loadModel('Notification');
                            $this->loadModel('UserNotification');
                            $notificationData['Notification']['subject'] = "Your task " . $currTask['UserTask']['task_title'] . " " . $this->request->data['UserTask']['status_completed'] . "% completed!";
                            $notificationData['Notification']['body'] = "Hey " . ($currTask['Client']['first_name'] . " " . $currTask['Client']['last_name']) . "! " . ($currTask['Admin']['first_name'] . " " . $currTask['Admin']['last_name']) . "  is hard at work on your task; " . ($currTask['UserTask']['task_title']) . "! Your task is now " . $this->request->data['UserTask']['status_completed'] . "% complete! Check in with your Web Agent for further details.";
                            $savedNotification = $this->Notification->save($notificationData);
                            if (!empty($savedNotification)) {
                                $userNotificationData['UserNotification']['notification_id'] = $this->Notification->id;
                                $userNotificationData['UserNotification']['sender_id'] = $this->Session->read('User.User.id');
                                $userNotificationData['UserNotification']['receiver_id'] = $currTask['Client']['id'];
                                $sucessId = $this->Notification->UserNotification->save($userNotificationData);
                            }
                        }
                    }
                    $this->Session->setFlash('Task has been updated successfully', 'default', 'success');
                    $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
                    exit;
                }
            } else {
                $str = '';
                foreach ($this->UserTask->validationErrors as $key => $error):
                    $str.=$error[0] . '<br/>';
                endforeach;
                $this->Session->setFlash('Task is not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
                exit;
            }
        } else {
            // $this->User->recursive = -1;
            $id = base64_decode($id);
            $this->UserTask->id = $id;
           
             $tastData_row = $this->TaskHistory->find("first", array('conditions' => array('TaskHistory.task_id' => $id), 'order' => 'TaskHistory.created desc'));
            $set_data = $this->UserTask->read();

            if (!empty($tastData_row)) {
              $set_data["UserTask"]["task_title"]= $tastData_row["TaskHistory"]["task_title"];
              $set_data["UserTask"]["task_description"]= $tastData_row["TaskHistory"]["task_description"];
              $set_data["UserTask"]["status_completed"]= $tastData_row["TaskHistory"]["status_completed"];
            }
            
           
            $this->request->data = $set_data;





            if ($this->RequestHandler->isAjax()) {
                $this->set('taskData', $this->request->data);
                $this->set('_serialize', array('taskData', 'tastData', 'PopupTitle'));
            }
        }
    }

    public function superadmin_delete_task($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->loadModel('Task');
        $this->Task->id = $id;
        if ($this->Task->delete()) {
            $this->Session->setFlash('Task has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Task is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true));
    }

    public function admin_delete_task($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->UserTask->id = $id;
        if ($this->UserTask->exists()) {
            $this->UserTask->id = $id;
            $this->UserTask->saveField('is_deleted', 1);
            $this->Session->setFlash('Task has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Task is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
    }

    public function admin_complete_task($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->UserTask->id = $id;
        $data = $this->UserTask->read();
        if ($this->UserTask->exists()) {
            $this->UserTask->id = $id;
            $this->UserTask->saveField('is_completed', 1);
            $this->UserTask->saveField('status_completed', 100);

            $this->loadModel('Notification');
            $notificationData['Notification']['subject'] = 'Finished Task';
            $notificationData['Notification']['body'] = 'Congratulations! Your task (' . $data['Task']['name'] . ') is now complete. Please contact your Web Agent for more details. Thank You.';
            $savedNotification = $this->Notification->save($notificationData);
            if (!empty($savedNotification)) {
                $this->request->data['UserNotification']['notification_id'] = $this->Notification->id;
                $this->request->data['UserNotification']['sender_id'] = $data['Admin']['id'];
                $this->request->data['UserNotification']['receiver_id'] = $data['Client']['id'];
                $sucessId = $this->Notification->UserNotification->saveAll($this->request->data['UserNotification']);
            }

            $this->Session->setFlash('Task has been completed successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Task is not marked as completed', 'default', 'error');
        }
        $this->redirect(array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true));
    }

    public function latestInProgressTasks() {
        $timeStart = time();
        $this->layout = 'ajax';
        $this->autoRender = false;
        $userId = $this->Session->read('User.User.id');
        if ($this->request->is('post')) {
            if (isset($this->request->data['timestamp']) && !empty($this->request->data['timestamp'])) {
                $timestamp = $this->request->data['timestamp'];
            } else {
                // get current database time
                $nowTime = $this->Task->getMySQLNowTimestamp();
                $timestamp = $nowTime[0][0]['timestamp'];
            }
        } else {
            $nowTime2 = $this->Task->getMySQLNowTimestamp();
            $timestamp = $nowTime2[0][0]['timestamp'];
        }
        $newData = false;
        $tasks = array();

        // loop while there is no new data and is running for less than 20 seconds
        while (!$newData && (time() - $timeStart) < 30) {
            // check for new data
            $tasksCount = $this->UserTask->getInProgressTasksCount();
            // pr($this->Message->getLastQuery());
            if (isset($tasksCount)) {
                $tasks[] = $tasksCount;
                $newData = true;
            }
            // let the server rest for a while
            usleep(1000000);
        }
        // get current database time
        $nowTime3 = $this->Task->getMySQLNowTimestamp();
        $timestamp = $nowTime3[0][0]['timestamp'];

        // output
        $data = array('tasks' => $tasks, 'timestamp' => $timestamp);
        echo json_encode($data);
        exit;
    }

}
