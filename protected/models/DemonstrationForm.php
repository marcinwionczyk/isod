<?php
class DemonstrationForm extends CFormModel
{
	public $name;
	public $category;
	public $category_Id;
	public $deviceIds;
	public $description;
	public $isNewRecord = true; 
	
	public function rules()
	{
		return array(
				array('name,description','required'),
				array('category,deviceIds,isNewRecord','safe'),
				);
	}
	public function attributeLabels()
	{
		return array(
				'name'=>'Nazwa',
				'category'=>'Kategoria',
				'description'=>'Opis demonstracji',
		);
	}
}