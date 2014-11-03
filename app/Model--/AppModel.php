<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	function uninvalidate($field) {
		if (isset($this->validate[$field])) {
			unset($this->validate[$field]);
		}
	}

	function unbindModelAll() {
		foreach (array(
		'hasOne' => array_keys($this->hasOne),
		'hasMany' => array_keys($this->hasMany),
		'belongsTo' => array_keys($this->belongsTo),
		'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany)
		) as $relation => $model) {
			$this->unbindModel(array($relation => $model));
		}
	}

	/**
	 * Unbinding list of Models from Model
	 * @author Dharmender <p>
	 * @param int $unbindModelsList <p>
	 * $unbindModelsList array array('Model1','Model2')
	 * An array with models which we want to unbind.
	 * </p>
	 * @return bool <b>TRUE</b>
	 */
	function unbindModelsList($unbindModelsList = 'all') {
		if (is_array($unbindModelsList) && !empty($unbindModelsList)) {
			$associations = array('hasMany', 'hasOne', 'belongsTo', 'hasAndBelongsToMany');
			foreach ($associations as $association) {
				$array = $this->$association;
				$modelList = array();
				foreach ($unbindModelsList as $model) {
					if (array_key_exists($model, $array)) {
						array_push($modelList, $model);
					}
				}
				if (!empty($modelList)) {
					$this->unbindModel(array($association => $modelList));
				}
			}
			return true;
		}
		$this->unbindModelAll();
		return true;
	}

	/**
	 * Strip all html tags from an array
	 *
	 * @param array $data
	 * @return array
	 */
	public function cleanHtml($data) {
		if (is_array($data)) {
			foreach ($data as $key => $var) {
				$data[$key] = $this->cleanHtml($var);
			}
		} else {
			$data = Sanitize::html($data, true);
		}

		return $data;
	}

	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}
		foreach ($this->_schema as $fieldName => $fieldType) {
			if ($fieldType['type'] == 'string') {
				if (!empty($this->data[$this->alias][$fieldName])) {
					if(!in_array($fieldName, Configure::read('ImagesColumns'))) {
						if(!is_array($this->data[$this->alias][$fieldName])) {
							$this->data[$this->alias][$fieldName] = strip_tags($this->data[$this->alias][$fieldName]);
						}
					} else {
						$this->data[$this->alias][$fieldName] = $this->data[$this->alias][$fieldName];
					}
				}
			}
		}
		return true;
	}
	
	public function getLastQuery() {
		$dbo = $this->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		return $lastLog['query'];
	}

	public function getMySQLNowTimestamp() {
		return $this->query('SELECT NOW() AS timestamp');
	}
}
