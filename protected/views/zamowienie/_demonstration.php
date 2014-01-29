<div class="view">
	<h4>Szczegóły demonstracji:</h4>
	<b>Nazwa kategorii:</b> <?php echo $model->demonstration->category->Name ?> <br/>
	<b>Nazwa demonstracji:</b> <?php echo $model->demonstration->Name ?><br/>
	<b>Opis:</b><br/><?php echo CHtml::decode($model->demonstration->Description) ?>
</div>