<?php

/**
 * This is the model class for table "demonstration".
 *
 * The followings are the available columns in table 'demonstration':
 * @property integer $Id
 * @property string $Name
 * @property string $Description
 * @property integer $Category_Id
 * @property string $create_time
 * @property string $update_time
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Order[] $orders
 */
	
class Demonstration extends ManyManyActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Demonstration the static model class
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
		return 'demonstration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Category_Id', 'required'),
			array('Category_Id', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>100),
			array('Description', 'length', 'max'=>1000),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, Name, Description, Category_Id, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}
	
	public function behaviors(){
		return array(
				'CTimestampBehavior' => array(
						'class' => 'zii.behaviors.CTimestampBehavior',
						'setUpdateOnCreate' => true
				)
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
			'category' => array(self::BELONGS_TO, 'Category', 'Category_Id'),
			//'devices' => array(self::MANY_MANY, 'Device', 'demonstration_has_device(Demonstration_Id, Device_Id)'),
			'orders' => array(self::HAS_MANY, 'Order', 'Demonstration_Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Name' => 'Nazwa demonstracji',
			'Description' => 'Opis',
			//'devices' => 'Urządzenia',
			'Category_Id' => 'Kategoria',
			'create_time' => 'Data utworzenia',
			'update_time' => 'Data aktualizacji',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Category_Id',$this->Category_Id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}