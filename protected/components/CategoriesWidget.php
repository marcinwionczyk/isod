<?php
Yii::import('zii.widgets.CPortlet');
Class CategoriesWidget extends CPortlet
{
	public function init()
	{
		$this->title = "Spis kategorii";
		return parent::init();
	}
	
	public function run()
	{
		$this->render('application.views.category.index');
		return parent::run();
	}
}

?>