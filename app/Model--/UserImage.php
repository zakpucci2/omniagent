<?php

App::uses('CakeTime', 'Utility');

class UserImage extends AppModel {

	public $name = 'UserImage';
	public $userImageName = '';
	public $cacheQueries = false;
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	public $validate = array(
		'image_name' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please upload image.'
			),
			'rule2' => array(
				'rule' => array(
					'extension',
					array('png', 'PNG', 'JPEG', 'JPG', 'jpeg', 'jpg', 'GIF', 'gif')
				),
				'message' => 'Please supply a .png, .jpeg, .gif format image.'
			)
		),
		'image_title' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter image title.'
			)
		),
		'image_description' => array(
			'rule1' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter image description.'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		if (!empty($this->data[$this->alias]['image_description'])) {
			$this->data[$this->alias]['image_description'] = strip_tags($this->data[$this->alias]['image_description']);
		}
		return true;
	}

	public function beforeDelete($cascade = true) {
		$this->recursive = -1;
		$this->userImageName = $this->findById($this->id);
	}

	public function afterDelete() {
		$largeImage = str_replace('\\', '/', WWW_ROOT) . 'img/gallery/' . $this->userImageName['UserImage']['image_name'];
		$thumbnailImage = str_replace('\\', '/', WWW_ROOT) . 'img/gallery/thumbnails320x200/' . $this->userImageName['UserImage']['image_name'];
		$fileLarge = new File($largeImage);
		$fileLarge->delete();
		$fileThumb = new File($thumbnailImage);
		$fileThumb->delete();
	}

}
