<?php

/*
 * ***********************************************************************************
 * FilesManager Controller
 * Functionality		 :	FilesManager related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

require_once APP . 'Lib' . DS . 'Elfinder' . DS . 'elFinderConnector.class.php';
require_once APP . 'Lib' . DS . 'Elfinder' . DS . 'elFinder.class.php';
require_once APP . 'Lib' . DS . 'Elfinder' . DS . 'elFinderVolumeDriver.class.php';
require_once APP . 'Lib' . DS . 'Elfinder' . DS . 'elFinderVolumeLocalFileSystem.class.php';

App::uses('CakeSession', 'Model/Datasource');
if (CakeSession::check('User')) {
    $currentUserSession = CakeSession::read('User');
    $currUserName = $currentUserSession['User']['user_name'];

    if (!is_dir(ELFINDER_DIR . $currUserName)) {
        mkdir(ELFINDER_DIR . $currUserName, 0755);
    }

    if (!defined('ELFINDER_DIR_USER')) {
        define('ELFINDER_DIR_USER', ELFINDER_DIR . $currUserName . '/');
    }
    if (!defined('ELFINDER_URL_USER')) {
        define('ELFINDER_URL_USER', ELFINDER_URL . $currUserName . '/');
    }
} else {
    CakeSession::setFlash('Unautorize access to page.', 'default', 'error');
    $AppController = new AppController();
    $AppController->redirect(array('controller' => 'users', 'action' => 'dashboard'));
}

function access($attr, $path, $data, $volume) {
    return strpos(basename($path), '.') === 0   // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
        : ($attr == 'read' || $attr == 'write');  // else set read+write to true, locked+hidden to false
}

function validName($name) {
    return strpos($name, '.') !== 0;
}

class FilesManagerController extends AppController {

    public $name = 'FilesManager';
    public $uses = array();
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Common');
    public $components = array('Email', 'RequestHandler', 'Security');
    public $opts = array(
        'debug' => true,
        'roots' => array(
            array(
                'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                'path' => ELFINDER_DIR_USER, // path to files (REQUIRED)
                'URL' => ELFINDER_URL_USER, // URL to files (REQUIRED)
                'tmbBgColor' => 'transparent',
                'uploadDeny' => array('text/x-php'),
                'attributes' => array(
                    array(// hide readmes
                        'pattern' => '/^.*\.(tmb|html|dll|exe|php|py|pl|sh|xml)$/i',
                        'read' => false,
                        'write' => false,
                        'locked' => true,
                        'hidden' => true
                    )
                ),
                'uploadMaxSize' => '10M',
                'acceptedName' => 'validName'
            // 'accessControl' => 'access'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Security->csrfCheck = false;
        $this->Security->validatePost = false;
    }

    public function index() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', "FilesManager Manager");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];

        if ($this->RequestHandler->isAjax() || $this->RequestHandler->isPost()) {
            $connector = new ElFinderConnector(new ElFinder($this->opts));
            $connector->run();
        }
    }

    public function admin_index() {
        $this->layout = 'admin_layout';
        $this->set('title_for_layout', "FilesManager Manager");
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];

        // if ($this->RequestHandler->isAjax() || $this->RequestHandler->isPost()) {
        $connector = new ElFinderConnector(new ElFinder($this->opts));
        $connector->run();
        // }
    }

}
