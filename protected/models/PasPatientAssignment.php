<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "pas_patient_assignment".
 *
 * The followings are the available columns in table 'pas_patient_assignment':
 * @property string $id
 * @property string $external_id
 * @property string $patient_id
 * @property string  $created_date
 * @property string  $last_modified_date
 * @property string  $created_user_id
 * @property string  $last_modified_user_id
 *
 * The followings are the available model relations:
 * @property Patient $patient
 * @property PAS_Patient $pas_patient
 */
class PasPatientAssignment extends BaseActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @return Phrase the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'pas_patient_assignment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('external_id, patient_id', 'required'),
			array('id, external_id, patient_id, created_date, last_modified_date, created_user_id, last_modified_user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
			'patient' => array(self::BELONGS_TO, 'Patient', 'patient_id'),
			'pas_patient' => array(self::BELONGS_TO, 'PAS_Patient', 'external_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('patient_id',$this->patient_id,true);
		$criteria->compare('external_id',$this->external_id,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('last_modified_date',$this->last_modified_date,true);
		$criteria->compare('created_user_id',$this->created_user_id,true);
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id,true);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function findByPatientId($patient_id) {
		return $this->find('patient_id = :patient_id', array(':patient_id' => (int) $patient_id));
	}
	
	public function findByExternalId($external_id) {
		return $this->find('external_id = :external_id', array(':external_id' => (int) $external_id));
	}
	
	public static function isStale($patient_id) {
		$record = self::model()->find('patient_id = :patient_id', array(':patient_id' => (int) $patient_id));
		return $record && (strtotime($record->last_modified_date) < (time() - self::PAS_CACHE_TIME));
	}
	
}