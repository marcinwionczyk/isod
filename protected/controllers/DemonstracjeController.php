<?php

class DemonstracjeController extends Controller
{
	public $layout='//layouts/category';
	
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'https +nowa, edycja, delete, view'
		);
	}
	public function filterHttps( $filterChain ) {
		$filter = new HttpsFilter;
		$filter->filter( $filterChain );
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>array('index'),
						'users'=>array('*'),
				),
				array('allow','actions'=>array('kategoria'),'users'=>array('@')),
				array('allow', 'actions'=>array('nowa','edycja','delete'),
						'roles'=>array('admin','editor')),
				array('allow','actions'=>array('view'),'roles'=>array('lecturer')),
				array('deny',  // deny all users
						'users'=>array('*')),
		);
	}
	public function actionIndex()
	{
		$this->redirect(array('/demonstracje/kategoria'));
	}
	public function actionKategoria($id=1)
	{
		$category = Category::model()->findByPk($id);
		$criteria = new CDbCriteria();
		$enabled=false;
		if ($category->Id == 1)
		{
			$criteria->order='t.Category_Id asc';
		}
		if ($category->rgt - $category->lft > 1)
		{	
			$criteria->with=array('category');
			$criteria->condition = 'category.lft > '.$category->lft.' AND category.rgt < '.$category->rgt;
			$criteria->order='t.Category_Id asc';
		}
		if ($category->rgt - $category->lft == 1)
		{		
			$criteria->condition = 'Category_Id = '.$id;
			$enabled=true;
		}		
		$demonstrations = Demonstration::model()->findAll($criteria);
		$panels = new CMap();
		foreach ($demonstrations as $demonstration)
		{
			$panels->add($demonstration->Name,$this->renderPartial('_demonstration', array('description'=> $demonstration->Description, 'demonstrationid' => $demonstration->Id),true));
		}
		$this->render('kategoria',array('categoryname'=>$category->Name,'categoryId'=>$category->Id,'panels'=>$panels,'enabled'=>$enabled));
	}
	
	public function actionNowa($id)
	{
		$model = new DemonstrationForm();
		$category = Category::model()->findByPk($id);
		if (isset($category))
			$model->category = $category->Name;
		if(isset($_POST['DemonstrationForm']))
		{
			$model->attributes=$_POST['DemonstrationForm'];
			$dmodel = new Demonstration();
			$dmodel->Name = $model->name;
			$dmodel->Description = $model->description;
			$dmodel->Category_Id = $id;
			if ($dmodel->save()) 
			{
				Dialog::Show("Pomyślnie zapisano nową demonstrację", "",Dialog::Message);
				$this->redirect(array('kategoria','id'=>$id));
			}
			Dialog::Show("Błąd", "Nie udało się zapisać nowej demonstracji!",Dialog::Message);
		}
		$this->render('nowa',array('model'=>$model));
	} 
	public function actionEdycja($id)
	{
		$model=$this->loadModel($id);
		$formModel = new DemonstrationForm();
		$formModel->isNewRecord = false;
		$formModel->name = $model->Name;
		$formModel->category = $model->category;
		$formModel->description = $model->Description;
		if(isset($_POST['DemonstrationForm']))
		{
			$formModel->attributes=$_POST['DemonstrationForm'];
			$model->Name = $formModel->name;
			$model->Description = $formModel->description;
			if($model->save())
			{
				Dialog::Show("Pomyślnie zaktualizowano demonstrację", "",Dialog::Message);
				$this->redirect(array('kategoria','id'=>$model->Category_Id));
			}
			Dialog::Show("Błąd", "Nie udało się zaktualizować demonstracji",Dialog::Message);
		}
		$this->render('edycja',array('model'=>$formModel));
	}
	public function actionDelete($id)
	{
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			$category = $model->Category_Id;
			$model->delete();
			Dialog::Show("Czy na pewno chcesz usunąć demonstrację?", "",Dialog::Confirmation);
			$this->redirect(array('kategoria','id'=>$category));
	}
	
	public function actionView($id)
	{
		$demonstration = $this->loadModel($id);
		$order = new Order();
		$order->user_id = Yii::app()->user->id;
		$order->Demonstration_Id = $id;
		$order->DateFrom = Yii::app()->user->getState('DateFrom');
		if (empty($order->DateFrom)) $order->DateFrom = date('Y-m-d H:i',mktime(7,30,0,date("m"),date("d")+1,date("Y")));
		$order->DateTo = Yii::app()->user->getState('DateTo');
		if (empty($order->DateTo)) $order->DateTo = date('Y-m-d H:i',mktime(9,0,0,date("m"),date("d")+1,date("Y")));
		$order->Room_Id = Yii::app()->user->getState('Room');
		$rooms = CHtml::listData(Room::model()->findAll(array('order'=>'Building, Number')), 'Id', 'Number','Building');
		if(isset($_POST['ajax']) && ($_POST['ajax']==='order-view-form'))
		{
			echo CActiveForm::validate($order);
			Yii::app()->end();
		}
		if(isset($_POST['Order']))
		{
			$order->attributes = $_POST['Order'];
			Ordering::isAvailable($order);
			if($order->validate())
			{
				if ($order->save()) { Dialog::Message(" ", "Zamówienie zostało zapisane");	}
				Yii::app()->user->setState('DateTo',$order->DateTo);
				Yii::app()->user->setState('DateFrom',$order->DateFrom);
				Yii::app()->user->setState('Room',$order->Room_Id);
				$this->redirect('/demonstracje/kategoria/'.$order->demonstration->category->Id);
			}
		}
		$this->render('view',array(
				'category'=> $demonstration->category->Name,
				'categoryId' => $demonstration->category->Id,
				'userid'=> Yii::app()->user->Id,
				'demonstrationid'=> $id,
				'name'=>$demonstration->Name,
				'model'=>$order,
				'minDate'=> date('Y-m-d',mktime(0,0,0,date("m"),date("d")+1,date("Y"))),
				'description'=>$demonstration->Description,
				'rooms'=>$rooms));		
	}
	
	public function loadModel($id)
	{
		$model=Demonstration::model()->with(array('category'))->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Żądana strona nie istnieje.');
		return $model;
	}
}