<?php

App::uses('AppHelper', 'View/Helper');
//App::uses('Helper', 'View');
App::uses('ConnectionManager', 'Model');
//App::uses('TimeHelper', 'View/Helper');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class CommonHelper extends AppHelper {

    public $helpers = array('Time');

    public function getAdmin_name($user_id = null) {
        App::import('Model', 'User');
        $User = new User();
        $res = $User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' => array('User.user_name')));
        if (!empty($res)) {
            return $res['User']['user_name'];
        }
    }

    function end_tour_now($arrData) {
        App::import('Model', 'User');
        $User = new User();
        $User->id = $arrData["User"]["id"];
        $User->saveField('is_tour_completed', 1);
    }

}
