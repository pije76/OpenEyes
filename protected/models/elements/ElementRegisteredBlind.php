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
 * This is the model class for table "element_registered_blind".
 *
 * The followings are the available columns in table 'element_registered_blind':
 * @property string $id
 * @property string $event_id
 * @property integer $status
 */
class ElementRegisteredBlind extends BaseElement
{
	const NOT_REGISTERED = 1;
	const SIGHT_IMPAIRED = 2;
	const SEVERELY_SIGHT_IMPAIRED = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementRegisteredBlind the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'element_registered_blind';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, event_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Fetch the options for registering as sight impaired
	 * @return array
	 */
	public function getSelectOptions()
	{
		return array(
			self::NOT_REGISTERED => 'Not Registered',
			self::SIGHT_IMPAIRED => 'Sight Impaired',
			self::SEVERELY_SIGHT_IMPAIRED => 'Severely Sight Impaired'
		);
	}

	/**
	 * Translate status constant into text value
	 *
	 * @return string
	 */
	public function getStatusText()
	{
		$text = '';
		switch ($this->status) {
			case self::NOT_REGISTERED:
				$text = 'Not Registered';
				break;
			case self::SIGHT_IMPAIRED:
				$text = 'Sight Impaired';
				break;
			case self::SEVERELY_SIGHT_IMPAIRED:
				$text = 'Severely Sight Impared';
				break;
		}
		return $text;
	}
}
