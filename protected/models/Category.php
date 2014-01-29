<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $Id
 * @property integer $ParentId
 * @property string $Name
 * @property integer $lft
 * @property integer $rgt
 *
 * The followings are the available model relations:
 * @property Category $parent
 * @property Category[] $categories
 * @property Demonstration[] $demonstrations
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
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
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ParentId, lft, rgt', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, ParentId, Name, lft, rgt', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Category', 'ParentId'),
			'categories' => array(self::HAS_MANY, 'Category', 'ParentId'),
			'demonstrations' => array(self::HAS_MANY, 'Demonstration', 'Category_Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'ParentId' => 'Parent',
			'Name' => 'Kategoria',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
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
		$criteria->compare('ParentId',$this->ParentId);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}