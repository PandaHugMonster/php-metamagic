<?php

namespace exp_classes;


use Attribute;

class MainInheritor extends MajorAncestor {

	#[ChildAttr2]
	protected $newItem1;

	protected $protProp2;
	private $privProp3;

	public $pubProp1Exposed;

	#[ChildAttr2]
	public function newFunc2() {}

//	#[ChildAttr2]
//	public function pubFunc1Exposed() {}
//	#[GenericAttr1]
//	protected function protFunc2Exposed() {}

}

class MajorAncestor {

	public $pubProp1;
	protected $protProp2;
	private $privProp3;

	#[GenericAttr1]
	public $pubProp1Exposed;
	#[GenericAttr1]
	protected $protProp2Exposed;
	#[GenericAttr1]
	private $privProp3Exposed;

	public function pubFunc1() {}
	protected function protFunc2() {}
	private function privFunc3() {}

	#[ChildAttr2]
	#[GenericAttr1]
	public function pubFunc1Exposed() {}
	#[GenericAttr1("test")]
	protected function protFunc2Exposed() {}
	#[GenericAttr1]
	private function privFunc3Exposed() {}

}


#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class GenericAttr1 {

	function __construct(...$args) {
	}

}

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class ChildAttr2 {

}
