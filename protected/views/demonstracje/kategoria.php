<?php
$this->breadcrumbs=array(
	'Demonstracje'=>array('demonstracje/kategoria'),
	$categoryname
);?> 
<?php //if(Yii::app()->user->roles=='lecturer') echo Ordering::renderFlashNotice(); ?>
<h1><?php echo($categoryname) ?></h1>
<?php if((Yii::app()->user->roles=='admin' || Yii::app()->user->roles=='editor') && $enabled) echo CHtml::link('dodaj nową demonstrację','/demonstracje/nowa/'.$categoryId).'<br/>' ?>
<?php if (count($panels) == 0) echo 'Brak demonstracji'; else 
	$this->widget('zii.widgets.jui.CJuiAccordion', array(
    'panels'=>$panels,
    // additional javascript options for the accordion plugin
    'options'=>array('collapsible'=>true, 'active'=>false, 'heightStyle'=>'content')
)); ?>
<p>

