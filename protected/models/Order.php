<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property string $Id
 * @property string $DateFrom
 * @property string $DateTo
 * @property integer $Demonstration_Id
 * @property integer $Room_Id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property Demonstration $demonstration
 * @property Room $room
 * @property User $user
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Order the static model class
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
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Room_Id, DateFrom, DateTo', 'required'),
			array('Demonstration_Id, Room_Id, user_id', 'numerical', 'integerOnly'=>true),
			array('DateFrom, DateTo', 'safe'),
			array('DateFrom, DateTo','date','format'=>'yyyy-MM-dd hh:mm','allowEmpty'=>true),
			array('DateTo','dateCompare','compareAttribute'=>'DateFrom','operator'=>'>','allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, DateFrom, DateTo, Demonstration_Id, Room_Id, user_id', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * 
	 */
	public function dateCompare($attribute,$params)
	{
		if (empty($params['compareAttribute']) || empty($params['operator']))
			$this->addError($attribute, 'Nieprawidłowe parametry dla reguły walidacji ');
		$compareTo=$this->$params['compareAttribute'];
		if($params['allowEmpty'] && (empty($this->$attribute) || empty($compareTo)))
			return;
		//set default format if not specified
		$format=(!empty($params['format']))? $params['format'] : 'yyyy-MM-dd hh:mm';
		if (empty($params['operator'])) $compare = ">";
		else $compare = $params['operator'];
		$start=CDateTimeParser::parse($this->$attribute,$format);
		$end=CDateTimeParser::parse($compareTo,$format);
		if (version_compare($start,$end,$compare)) {
			return;
		} else {
			$this->addError($attribute, "Data oddania demonstracji jest mniejsza niż data wypożyczenia");
		}
	}
	
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'demonstration' => array(self::BELONGS_TO, 'Demonstration', 'Demonstration_Id'),
			'room' => array(self::BELONGS_TO, 'Room', 'Room_Id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'DateFrom' => 'Dzień i godzina wypożyczenia demonstracji',
			'DateTo' => 'Dzień i godzina oddania demonstracji',
			'Demonstration_Id' => 'Nazwa demonstracji',
			'Room_Id' => 'Sala',
			'user_id' => 'Wykładowca',
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
		$criteria->compare('DateFrom',$this->DateFrom,true);
		$criteria->compare('DateTo',$this->DateTo,true);
		$criteria->addCondition("`DateFrom` >= NOW()");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}