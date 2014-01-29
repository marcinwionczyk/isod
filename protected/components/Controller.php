<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $pageTitle = 'Internetowy System Obsługi Demonstracji z fizyki';
	public $pageKeywords = 'Instytut Fizyki, Politechnika Wrocławska, Internetowy System Obsługi Demonstracji z fizyki, Marcin Wionczyk, Michał Nowakowski, ISOD, isod, internetowy system obsługi demonstracji';
	public $pageDesc = 'Instytut Fizyki, Politechnika Wrocławska, Internetowy System Obsługi Demonstracji z fizyki,ISOD, isod, internetowy system obsługi demonstracji';
	public $pageRobotsIndex = false;
	public $pageAuthor = 'Marcin Wionczyk';
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	public function display_seo()
	{
	    // STANDARD TAGS
	    // -------------------------
	    // Title/Desc
	    echo "\t".''.PHP_EOL;
	    echo "\t".'<meta name="keywords" content="',CHtml::encode($this->pageKeywords),'">'.PHP_EOL;
	    echo "\t".'<meta name="description" content="',CHtml::encode($this->pageDesc),'">'.PHP_EOL;
            echo "\t".'<meta name="author" content="',CHtml::encode($this->pageDesc),'">'.PHP_EOL;	
	    // Option for NoIndex
	    if ( $this->pageRobotsIndex == false ) {
	        echo '<meta name="robots" content="noindex">'.PHP_EOL;
	    }
	}
}
