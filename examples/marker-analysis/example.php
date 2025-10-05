<?php

use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellMethod;

require_once '../../vendor/autoload.php';


class MyOldCode {

	#[\Deprecated("I don't like this method 1")]
	public function obsoleteMethod1() {}

	public function obsoleteMethod2() {}

	#[\Deprecated("I don't like this method 3")]
	public function obsoleteMethod3() {}

	static public function obsoleteMethod4() {}

	#[\Deprecated("I don't like this method 5")]
	static public function obsoleteMethod5() {}

}

$deprecated_methods = MetaMagic::findSpells(
	refs: MyOldCode::class,
	attrs: \Deprecated::class,
	types: TargetType::MethodType,
);

foreach ($deprecated_methods as $method) {
	/** @var SpellMethod $method */
	print_r(
		"Deprecated method found: ".
		"{$method->target->reflection->class}::".
		"{$method->target->reflection->name}()\n"
	);
	/** @var \Deprecated $attr */
	$attr = $method->attr;
	print_r(
		"Deprecation message: ".
		"{$attr->message}\n"
	);
	echo "--------------\n";
}
