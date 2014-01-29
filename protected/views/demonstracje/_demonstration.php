
<div class="view">
	<h4>Opis</h4>
	<?php echo $description; ?>
</div>
<div class="view">
</div>
<?php 
if(Yii::app()->user->roles=='admin' || Yii::app()->user->roles=='editor')
{
	echo CHtml::link('edycja','/demonstracje/edycja/'.$demonstrationid).' ';
	echo CHtml::link('usuń','/demonstracje/delete/'.$demonstrationid);
}
if(Yii::app()->user->roles=='lecturer')
{
	echo CHtml::link('zamów','/demonstracje/view/'.$demonstrationid);
}
?>

