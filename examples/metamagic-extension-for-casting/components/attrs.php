<?php


#[Attribute(Attribute::TARGET_METHOD)]
class ToFloat {

}

#[Attribute(Attribute::TARGET_METHOD)]
class ToInt {

}

#[Attribute(Attribute::TARGET_METHOD)]
class ToStr {

}

#[Attribute(Attribute::TARGET_METHOD)]
class ToBool {

}

#[Attribute(Attribute::TARGET_METHOD)]
class ToArray {

}

#[Attribute(Attribute::TARGET_METHOD)]
class ToObj {

	/**
	 * @param class-string $class
	 */
	function __construct(public string $class) {}

}
