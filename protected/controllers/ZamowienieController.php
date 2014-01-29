<?php

class ZamowienieController extends Controller
{
	public $layout='//layouts/column1';
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'https +view, delete, update'
		);
	}
	public function filterHttps( $filterChain ) {
		$filter = new HttpsFilter;
		$filter->filter( $filterChain );
	}
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>array('index','delete','view','update'),
						'roles'=>array('lecturer'),
				),
				array('deny',  // deny all users
						'users'=>array('*')),
		);
	}
	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'DateFrom >= NOW() AND user_id=:userid';
		$criteria->params = array(':userid'=>Yii::app()->user->id);
		$model = new CActiveDataProvider('Order',array(
				'criteria'=>$criteria,
				'pagination'=>array('pagesize'=>20),
				'sort'=>array(
						'attributes'=>array('room.Number','DateFrom','DateTo'),
						'defaultOrder'=>array('DateFrom'=>true))
				));
		$this->render('index',array(
				'model'=>$model,
		));
		
	}
	public function actionView($id)
	{
		$this->layout='//layouts/column2';
		$this->render('view',array('model'=>$this->loadModel($id))); 
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Nieprawidłowe zapytanie - żądanie nie może być obsłużone przez serwer z powodu błędnej składni zapytania.');
	}
	public function actionUpdate($id)
	{
		$this->layout='//layouts/column2';
		$model = $this->loadModel($id);
		$model->DateFrom = Yii::app()->dateFormatter->format("yyyy-MM-dd hh:mm", strtotime($model->DateFrom));
		$model->DateTo = Yii::app()->dateFormatter->format("yyyy-MM-dd hh:mm", strtotime($model->DateTo));
		$rooms = CHtml::listData(Room::model()->findAll(array('order'=>'Building, Number')), 'Id', 'Number','Building');
		
		if(isset($_POST['Order']))
		{
			$model->attributes = $_POST['Order'];
			Ordering::isAvailable($model);
			if($model->validate())
			{
				$model->save();
				Yii::app()->user->setState('DateTo',$model->DateTo);
				Yii::app()->user->setState('DateFrom',$model->DateFrom);
				Yii::app()->user->setState('Room',$model->Room_Id);
				$this->redirect('/zamowienie');
				return;
			}
		}
		$this->render('update',array('model'=>$model,
				'rooms'=>$rooms,
				'minDate'=>date('Y-m-d',mktime(0,0,0,date("m"),date("d")+1,date("Y")))
				));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Order::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Nie znaleziono - serwer nie odnalazł zasobu według podanego URL ani niczego co by wskazywało na istnienie takiego zasobu w przeszłości.');
		return $model;
	}
	
}