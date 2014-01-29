<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="pl" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">
				<div class="container">
					<div class="span-7 first"><img src="/images/znak-pwr_poziom-pl-kolor.jpg"/></div>
					<div class="span-18 last" style="padding-top: 38px; text-align: center"><b>I</b>nternetowy <b>S</b>ystem <b>O</b>bsługi <b>D</b>emonstracji z fizyki</div>
				</div>
		</div>		
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Start', 'url'=>array('/site/index')),
				array('label'=>'Demonstracje', 'url'=>array('/demonstracje/kategoria'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Moje zamówienia', 'url'=>array('/zamowienie/index'),'visible'=>Yii::app()->user->checkAccess('lecturer')),
				array('label'=>'Zamówienia', 'url'=>array('/zamowienia/index'),'visible'=>Yii::app()->user->checkAccess('repository')),
				array('label'=>'Urządzenia','url'=>array('/urzadzenie'),'visible'=>(Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('editor'))),
				array('label'=>'Użytkownicy','url'=>array('/user'),'visible'=>Yii::app()->user->checkAccess('admin')),
				array('label'=>'Role','url'=>array('/role'),'visible'=>Yii::app()->user->checkAccess('admin')),
				array('label'=>'Sale wykładowe','url'=>array('/room'),'visible'=>Yii::app()->user->checkAccess('admin')),
				array('label'=>'Zaloguj się', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Wyloguj się ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),			
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'homeLink' => CHtml::link('Start',Yii::app()->homeUrl),
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	<?php $this->renderPartial('//site/dialog'); ?>
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<?php $this->widget('ext.ScrollTop',array('label' => '&uarr; przewiń do góry &uarr;')); ?> <br /><br/>
		<p><span title="prawa autorskie">&#169;</span> 2013 - <a href="http://marcin.wionczyk.name" target="_blank">Marcin Wionczyk</a>  (wykonanie strony), Michał Nowakowski (treść).</p> <a href="http://www.yiiframework.com/" target="_blank"><img src="/images/yii-powered.png"/></a>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
