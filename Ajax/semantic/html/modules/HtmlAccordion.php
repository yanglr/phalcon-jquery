<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\content\HtmlAccordionItem;
use Ajax\JsUtils;

class HtmlAccordion extends HtmlSemCollection{

	protected $params=array();

	public function __construct( $identifier, $tagName="div", $baseClass="ui"){
		parent::__construct( $identifier, "div", "ui accordion");
	}


	protected function createItem($value){
		$count=$this->count();
		$title=$value;
		$content=NULL;
		if(\is_array($value)){
			$title=@$value[0];$content=@$value[1];
		}
		return new HtmlAccordionItem("item-".$this->identifier."-".$count, $title,$content);
	}

	protected function createCondition($value){
		return ($value instanceof HtmlAccordionItem)===false;
	}

	public function addPanel($title,$content){
		return $this->addItem([$title,$content]);
	}

	/**
	 * render the content of $controller::$action and set the response to a new panel
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param Controller $initialController
	 * @param string $controller a Phalcon controller
	 * @param string $action a Phalcon action
	 */
	public function forwardPanel(JsUtils $js,$title,$initialController,$controller,$action){
		return $this->addPanel($title, $js->forward($initialController, $controller, $action));
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false)
			$this->_bsComponent=$js->semantic()->accordion("#".$this->identifier,$this->params);
			$this->addEventsOnRun($js);
			return $this->_bsComponent;
	}

	public function setStyled(){
		return $this->addToProperty("class", "styled");
	}

	public function setActive($index){
		$this->getItem($index)->setActive(true);
		return $this;
	}

	public function setExclusive($value){
		$this->params["exclusive"]=$value;
	}
}