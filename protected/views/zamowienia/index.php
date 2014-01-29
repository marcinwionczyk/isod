
<h2>Zamówienia na dziś</h2>
<?php
$this->widget('zii.widgets.CListView',array(
		'dataProvider'=>$orders4today,
		'itemView'=>'_view'
		));
?>

<h2>Zamówienia na jutro</h2>
<?php 
$this->widget('zii.widgets.CListView',array(
		'dataProvider'=>$orders4tommorow,
		'itemView'=>'_view'
));
?>
