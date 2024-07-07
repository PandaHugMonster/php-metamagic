<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace initial\samples;


//meta_magic_objects_of_default_attributes.php

use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\attributes\magic\Del;
use spaf\metamagic\attributes\magic\Get;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\attributes\magic\IsAssigned;
use spaf\metamagic\attributes\magic\Set;
use spaf\metamagic\attributes\magic\ToString;
use spaf\metamagic\traits\MagicMethodsTrait;
use function implode;
use function print_r;

class ObjectForTest1 {
	use MagicMethodsTrait;

	private $_internal_vars = [];
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

	#[Del]
	function beforeDeletionMethod(string $name) {
		echo "unset of {$name} is called";
	}

	#[IsAssigned]
	function myIsAssigned(string $name) {
		return isset($this->_internal_vars[$name]);
	}

	#[Get]
	function myTotalGetter(string $name): mixed {
		return $this->_internal_vars[$name];
	}

	#[Set]
	function myTotalSetter(string $name, mixed $val): void {
		$this->_internal_vars[$name] = $val;
	}

	#[Invoke]
	function myInvoke(): string {
		return "Object Was Invoked";
	}

	#[ToString(null_conversion: false)]
	function myToString(): string {
		return implode(",", $this->_internal_vars);
	}

}

class ObjectForTest2 {
	use MagicMethodsTrait;

	public string|null $val1 = null;

}

class ObjectForTest3 {
	use MagicMethodsTrait;

	public $internal_vars = [];

	#[Get]
	function myTotalGetter(string $name): mixed {
		return $this->internal_vars[$name];
	}

	#[Set]
	function myTotalSetter(string $name, mixed $val): void {
		$this->internal_vars[$name] = $val;
	}

	#[ToString(null_conversion: false)]
	function myToString(): string {
		return implode(",", $this->_internal_vars);
	}

}

class ObjectForTest4 {
	use MagicMethodsTrait;

	#[ToString(null_conversion: true)]
	function myToString(): ?string {
		return null;
	}
}

class ObjectForTest5 {
	use MagicMethodsTrait;

	#[ToString(null_conversion: false)]
	function myToString(): ?string {
		return null;
	}
}