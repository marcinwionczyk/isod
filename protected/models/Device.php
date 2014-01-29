<?php

/**
 * This is the model class for table "device".
 *
 * The followings are the available columns in table 'device':
 * @property integer $Id
 * @property string $EvidenceId
 * @property string $Name
 * @property string $Description
 * @property string $Instruction
 * @property string $Place
 *
 * The followings are the available model relations:
 * @property Demonstration[] $demonstrations
 */
class Device extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Device the static model class
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
		return 'device';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name', 'required'),
			array('EvidenceId, Name, Place', 'length', 'max'=>100),
			array('Description, Instruction', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, EvidenceId, Name, Description, Instruction, Place', 'safe', 'on'=>'search'),
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
			'demonstrations' => array(self::MANY_MANY, 'Demonstration', 'demonstration_has_device(Device_Id, Demonstration_Id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'EvidenceId' => 'Nr ewidencyjny',
			'Name' => 'Nazwa',
			'Description' => 'Opis',
			'Instruction' => 'Instrukcja',
			'Place' => 'Miejsce',
		);
	}
	public function getConcatened()
	{
		return $this->Name.' | '.$this->Place;
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
		$criteria->compare('EvidenceId',$this->EvidenceId,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Instruction',$this->Instruction,true);
		$criteria->compare('Place',$this->Place,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}