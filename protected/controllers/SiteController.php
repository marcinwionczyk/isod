<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

public function filters()
{
    return array(
        'https +login', // Force https, but only on login page
    );
}

	public function filterHttps( $filterChain ) {
		$filter = new HttpsFilter;
		$filter->filter( $filterChain );
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */

	public function actionIndex()
	{
		if(!isset(Yii::app()->request->cookies['acceptCookies']))
		{
				Dialog::Show("Czy zgadzasz się na zapisywanie ciasteczek na twoim komputerze?", "Na podstawie przepisów prawa (Art. 173, 174 oraz Art. 209 znowelizowanej ustawy Prawo Telekomunikacyjne) informuję, że aplikacja internetowa isod.pwr.wroc.pl wykorzystuje pliki cookies (Ciasteczka). Są to niewielkie pliki tekstowe wysyłane przez serwis internetowy, który odwiedza internauta, do urządzenia internauty. Są one niezbędne do poprawnego funkcjonowania systemu ISOD.",Dialog::Cookies);
		}
		$number = Demonstration::model()->count();
		$criteria = new CDbCriteria();
		$criteria->order='t.create_time desc';
		$criteria->limit = 3;
		$demonstrations = Demonstration::model()->findAll($criteria);
		$panels = new CMap();
		foreach($demonstrations as $demonstration)
		{
			$panels->add($demonstration->Name,$this->renderPartial('_demonstration', array('description'=> $demonstration->Description),true));
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',array('number'=>$number, 'panels'=>$panels));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		//use SSL in login form	
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	public function actionAcceptcookie()
	{
		$cookie = new CHttpCookie('acceptCookies', true);
		$cookie->expire = time()+31104000;
		Yii::app()->request->cookies['acceptCookies'] = $cookie;
		$this->redirect(Yii::app()->homeUrl);
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}