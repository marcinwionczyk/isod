<?php
class ZamowieniaController extends Controller
{
	public $layout='//layouts/column1';
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'https'
		);
	}
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
						'actions'=>array('index'),
						'roles'=>array('repository'),
				),
				array('deny',  // deny all users
						'users'=>array('*')),
		);
	}
	public function filterHttps( $filterChain ) {
		$filter = new HttpsFilter;
		$filter->filter( $filterChain );
	}
	public function actionIndex()
	{
		$todayFrom = date('Y-m-d H:i',mktime(7,0,0,date("m"),date("d"),date("Y")));
		$todayTo = date('Y-m-d H:i',mktime(22,0,0,date("m"),date("d"),date("Y"))); 
		$tommorowFrom = date('Y-m-d H:i',mktime(7,0,0,date("m"),date("d")+1,date("Y")));
		$tommorowTo = date('Y-m-d H:i',mktime(22,0,0,date("m"),date("d")+1,date("Y"))); 
		$criteria1 = new CDbCriteria();
		$criteria1->addBetweenCondition('DateFrom', $todayFrom, $todayTo,'AND');
		$criteria1->with = array('room','user','demonstration');
		$criteria2 = new CDbCriteria();
		$criteria2->addBetweenCondition('DateFrom', $tommorowFrom, $tommorowTo,'AND');
		$criteria2->with = array('room','user','demonstration');
		$orders4today = new CActiveDataProvider('Order',array(
				'criteria'=> $criteria1,
				'pagination'=>array('pageSize'=>20),
				'sort'=>array(
						'attributes'=>array('room.Number','DateFrom','DateTo'),
						'defaultOrder'=>array('DateFrom'=>true)
				)));
		$orders4tommorow = new CActiveDataProvider('Order',array(
				'criteria'=>$criteria2,
				'pagination'=>array('pageSize'=>20),
				'sort'=>array(
						'attributes'=>array('room.Number','DateFrom','DateTo'),
						'defaultOrder'=>array('DateFrom'=>true)
		)));
		//$orders4today = Order::model()->with('demonstration','room','user')->findAll($criteria4today);
		//$orders4tommorow = Order::model()->with('demonstration','room','user')->findAll($criteria4tommorow);
		$this->render('index',array('orders4today'=>$orders4today, 
				'orders4tommorow'=>$orders4tommorow, 
				));
		
		
	}
	
}