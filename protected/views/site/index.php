
<h2>Cel powstania systemu ISOD</h2>
    <p>System ISOD powstał w celu: </p>
        <ul>
            <li>dania wykładowcom możliwości:
                <ul>
                    <li>dokładnego zorienowania się w zasobach Zbiorów oraz ich zdalnego zamawiania. Każda z demonstracji jest dokładnie opisana. Oprócz standardowych demonstracji, w systemie zawarte są także odnośniki do stron z symulacjami zjawisk fizycznych i ciekawymi animacjami.</li>
                    <li>zgłaszania propozycji wykonania nowych demonstracji,</li>
                </ul> 
            </li>
            <li>umożliwienia pracownikom Zbiorów przygotowania demonstracji wraz z odpowiednim wyprzedzeniem czasowym.</li>
        </ul>
        
    <?php if(Yii::app()->user->isGuest)
    {
    	echo '<p>Aby skorzystać z systemu, należy się '.CHtml::link('zalogować',array('/site/login')).'.</p>';
    }
    else
    {
    	echo '<p>Aktualnie w systemie jest zapisane '.$number.' demonstracji, z których 3 ostatnio dodane to: </p>';
    	$this->widget('zii.widgets.jui.CJuiAccordion', array(
    			'panels'=>$panels,
    			// additional javascript options for the accordion plugin
    			'options'=>array('collapsible'=>true, 'active'=>false, 'heightStyle'=>'content')
    	));
    }?>
    
    

