<?php

/*
 * ***********************************************************************************
 * Messages Controller
 * Functionality		 :	Messages related function used for all types of users
 * including super administrator,client user,QA type of users,admin type of users
 * ***********************************************************************************
 */

App::uses('AppController', 'Controller');
// App::uses('ConnectionManager', 'Model');
App::uses('Sanitize', 'Utility');

class GalleryController extends AppController {

    public $name = 'Gallery';
    public $uses = array('User', 'UserImage');
    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Paginator', 'Common', 'Time');
    public $components = array('Email', 'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function listimages() {
        $this->layout = 'user_layout';
        $condition = array(
            'UserImage.user_id' => $this->Session->read('User.User.id'),
            'UserImage.is_active' => 1
        );
        $this->paginate = array(
            'conditions' => $condition,
            'limit' => Configure::read('LIST_NUM_RECORDS.Gallery'),
            'order' => 'UserImage.id DESC'
        );
        $data = $this->paginate('UserImage');
        $this->set(compact('data'));
    }

    public function upload_photo() {
        $this->layout = 'user_layout';
        $this->set('title_for_layout', 'Upload Image');
        $currentUserSession = $this->Session->read('User');
        $id = $currentUserSession['User']['id'];
        $this->set('userID', $id);
        if ($this->request->is('post')) {
            if (empty($this->request->data['UserImage']['image']['name'])) {
                $this->Session->setFlash('Please upload image', 'default', 'error');
                $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
            } else {
                $this->request->data['UserImage']['image_name'] = $this->request->data['UserImage']['image']['name'];
            }
            $this->UserImage->set($this->request->data);
            if ($this->UserImage->validates()) {
                $pictureTempName = $this->request->data['UserImage']['image']['tmp_name'];
                $pictureName = $this->request->data['UserImage']['image']['name'];
                $pictureType = $this->request->data['UserImage']['image']['type'];
                $ext = explode('.', $pictureName);
                $ext = end($ext);
                if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                    $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                    $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
                } else {
                    $image = $this->generateRandomString(5) . '.' . $ext;
                    $uploadFolder = 'gallery';
                    $destGalleryPath = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                    copy($pictureTempName, $destGalleryPath);
                    $uploadFolder = 'gallery/thumbnails320x200';
                    App::import('Component', 'Resize');
                    $ResizeComp = new ResizeComponent();
                    $logos = array(Configure::read('IMAGE_SIZES.GalleryImage.width') => $image);
                    $dimentions = array(Configure::read('IMAGE_SIZES.GalleryImage.width') => Configure::read('IMAGE_SIZES.GalleryImage.height'));
                    foreach ($dimentions as $picWidth => $picHeight) {
                        $destination = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $logos[$picWidth];
                        $ResizeComp->resize_fill($pictureTempName, $destination, $picWidth, $picHeight);
                        /* if ($width <= $picWidth && $height <= $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination, 'as_define', $width, $height, 0, 0, 0, 0, 0);
                          } else if ($width > $picWidth) {
                          $ResizeComp->resize($pictureTempName, $destination, 'width', $picWidth, $picHeight, 0, 0, 0, 0, 0);
                          } else if ($height > $picHeight) {
                          $ResizeComp->resize($pictureTempName, $destination, 'height', $picWidth, $picHeight, 0, 0, 0, 0);
                          } */
                    }
                    $this->request->data['UserImage']['user_id'] = $currentUserSession['User']['id'];
                    $this->request->data['UserImage']['image_name'] = $image;
                    if ($this->UserImage->save($this->request->data, false)) {
                        $this->Session->setFlash('Gallery Image uploaded successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
                    }
                }
            } else {
                $str = '';
                foreach ($this->UserImage->validationErrors as $error) {
                    $str .= $error[0] . '<br/>';
                }
                $this->Session->setFlash('Gallery image not uploaded <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
            }
        }
    }

    public function update_photo($id = null) {
        $PopupTitle = "Update Image Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->request->is('post')) {
            if (!empty($this->request->data['UserImage']['image']['name'])) {
                $this->request->data['UserImage']['image_name'] = $this->request->data['UserImage']['image']['name'];
            }
            $this->UserImage->set($this->request->data);
            if ($this->UserImage->validates()) {
                if (isset($this->request->data['UserImage']['image']['name']) && !empty($this->request->data['UserImage']['image']['name'])) {
                    $pictureTempName = $this->request->data['UserImage']['image']['tmp_name'];
                    $pictureName = $this->request->data['UserImage']['image']['name'];
                    $pictureType = $this->request->data['UserImage']['image']['type'];
                    $ext = explode('.', $pictureName);
                    $ext = end($ext);
                    if ($pictureType != 'image/png' && $pictureType != 'image/jpeg' && $pictureType != 'image/gif') {
                        $this->Session->setFlash('Please upload png/jpg/gif format only', 'default', 'message');
                        $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
                    } else {
                        $image = $this->generateRandomString(5) . '.' . $ext;
                        $uploadFolder = 'gallery';
                        $destGalleryPath = str_replace('\\', '/', WWW_ROOT) . 'img/' . $uploadFolder . '/' . $image;
                        copy($pictureTempName, $destGalleryPath);
                        $uploadFolder = 'gallery/thumbnails320x200';
                        App::import('Component', 'Resize');
                        $ResizeComp = new ResizeComponent();
                        $logos = array(Configure::read('IMAGE_SIZES.GalleryImage.width') => $image);
                        $dimentions = array(Configure::read('IMAGE_SIZES.GalleryImage.width') => Configure::read('IMAGE_SIZES.GalleryImage.height'));
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
                        $this->UserImage->id = $id;
                        $this->request->data['UserImage']['image_name'] = $image;
                        if ($this->UserImage->save($this->request->data, false)) {
                            $this->Session->setFlash('Gallery Image uploaded successfully', 'default', 'success');
                            $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
                        }
                    }
                } else {
                    $this->UserImage->id = $id;
                    if ($this->UserImage->save($this->request->data, false)) {
                        $this->Session->setFlash('Gallery Image updated successfully', 'default', 'success');
                        $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
                    }
                }
            } else {
                $str = '';
                foreach ($this->UserImage->validationErrors as $error) {
                    $str .= $error[0] . '<br/>';
                }
                $this->Session->setFlash('Gallery image not updated <br/>' . $str, 'default', 'error');
                $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
            }
        } else {
            if ($this->RequestHandler->isAjax()) {
                $this->UserImage->id = $id;
                $this->UserImage->recursive = -1;
                $image = $this->UserImage->findById($id);
                $this->set('image', $image);
                $this->set('_serialize', array('image', 'PopupTitle'));
            }
        }
    }

    public function delete_image($id = null) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->UserImage->id = $id;
        if ($this->UserImage->exists()) {
            $this->UserImage->delete();
            $this->Session->setFlash('Gallery Image has been deleted successfully', 'default', 'success');
        } else {
            $this->Session->setFlash('Gallery Image is not deleted', 'default', 'error');
        }
        $this->redirect(array('controller' => 'gallery', 'action' => 'listimages'));
    }

    public function view_image($id = null) {
        // $this->autoRender = false;
        $PopupTitle = "Gallery Image Details";
        $id = base64_decode($id);
        $this->set("PopupTitle", $PopupTitle);
        if ($this->RequestHandler->isAjax()) {
            $this->UserImage->id = $id;
            $this->UserImage->recursive = -1;
            $image = $this->UserImage->read();
            $this->set('image', $image);
            $this->set('imageId', base64_encode($id));
            $this->set('_serialize', array('image', 'PopupTitle', 'imageId'));
        }
    }

    public function download_photo($id = null, $fullImage = 0) {
        $this->autoRender = false;
        $id = base64_decode($id);
        $this->UserImage->id = $id;
        if ($this->UserImage->exists()) {
            $this->UserImage->recursive = -1;
            $imageData = $this->UserImage->read();
            $filename = $imageData['UserImage']['image_name'];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if ($fullImage == 1) {
                $path = 'webroot/img/gallery/' . $imageData['UserImage']['image_name'];
            } else {
                $path = 'webroot/img/gallery/thumbnails320x200/' . $imageData['UserImage']['image_name'];
            }
            $this->response->file($path, array(
                'download' => true,
                'name' => md5($filename) . '.' . $extension,
            ));
            return $this->response;
        }
    }

}
