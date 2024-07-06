<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace initial\samples;


//meta_magic_objects_of_default_attributes.php

use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\traits\MagicMethodsTrait;

class ObjectForTest1 {
	use MagicMethodsTrait;

	public string|null $val1 = null;

	#[DebugInfo]
	function debugInfoMethod() {
		return ["info1" => "val1", "info2" => "val2"];
	}

	#[CopyShallow]
	function copyShallowMethod() {
		$this->val1 = "I'm a copy";
	}

	#[Call]
	function dynamicCallMethod($name) {
		return "dynamicCallMethod {$name}";
	}

	#[Call]
	static function staticCallMethod($name) {
		return "staticCallMethod {$name}";
	}
}