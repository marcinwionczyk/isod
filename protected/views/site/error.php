<?php
$this->pageTitle=Yii::app()->name . ' - Błąd';
$this->breadcrumbs=array(
	'Błąd',
);
?>

<h2>Błąd nr <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>