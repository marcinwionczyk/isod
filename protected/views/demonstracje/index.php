<?php
$this->breadcrumbs=array(
	'Demonstracje',
);?> 
<h1><?php echo($categoryname) ?></h1>
<?php
	$this->widget('zii.widgets.jui.CJuiAccordion', array(
			'panels'=>$panels,
			'options'=>array('animated'=>'slide','autoHeight'=>false,'active'=>false)
			));
?>

