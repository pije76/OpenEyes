<?php

/**
 * This is the model class for table "element_intraocular_pressure".
 *
 * The followings are the available columns in table 'element_intraocular_pressure':
 * @property string $id
 * @property string $event_id
 * @property integer $right_iop
 * @property integer $left_iop
 */
class ElementIntraocularPressure extends BaseElement
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementIntraocularPressure the static model class
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
		return 'element_intraocular_pressure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('right_iop, left_iop, event_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, right_iop, left_iop', 'safe', 'on'=>'search'),
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
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
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
			'right_iop' => 'Right Eye',
			'left_iop' => 'Left Eye',
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
		$criteria->compare('right_iop',$this->right_iop);
		$criteria->compare('left_iop',$this->left_iop);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Get an array of the valid select box options for IOP
	 * @return array
	 */
	public function getSelectOptions()
	{
		$iopValues = array();
		for ($i = 0; $i < 81; $i++) {
			$iopValues[$i] = $i;
		}

		return $iopValues;
	}
}