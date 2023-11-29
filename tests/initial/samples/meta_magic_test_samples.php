<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace initial\samples;

use Attribute;
use spaf\simputils\attributes\markers\Shortcut;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_ALL)]
class SimpleRepeatableAttribute {

}

#[Attribute]
class SimpleForAllAttribute {

}

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class SimpleLimitedAttribute {

}

#[SimpleForAllAttribute]
class VeryCommonClass {

	#[SimpleForAllAttribute]
	public const CONST_PUBLIC_1 = 1;

	#[SimpleForAllAttribute]
	protected const CONST_PROTECTED_2 = 2;

	#[SimpleForAllAttribute]
	private const CONST_PRIVATE_3 = 3;


	#[SimpleRepeatableAttribute]
	#[SimpleRepeatableAttribute]
	#[SimpleRepeatableAttribute]
	#[SimpleForAllAttribute]
	static public $static_public_field_4;

	#[SimpleRepeatableAttribute]
	#[SimpleRepeatableAttribute]
	#[SimpleForAllAttribute]
	static protected $static_protected_field_5;

	#[SimpleRepeatableAttribute]
	#[SimpleForAllAttribute]
	static private $static_private_field_6;


	#[SimpleForAllAttribute]
	public $dynamic_public_field_7;

	#[SimpleForAllAttribute]
	protected $dynamic_protected_field_8;

	#[SimpleForAllAttribute]
	private $dynamic_private_field_9;


	#[SimpleForAllAttribute]
	function __construct() {

	}


	#[Shortcut("TEST SHORTCUT 1")]
	#[SimpleForAllAttribute]
	public function dynamicPublicMethod11() {

	}

	#[SimpleForAllAttribute]
	protected function dynamicProtectedMethod12() {

	}

	#[SimpleLimitedAttribute]
	#[SimpleForAllAttribute]
	private function dynamicPrivateMethod13() {

	}


	#[SimpleForAllAttribute]
	static public function staticPublicMethod14() {

	}

	#[Shortcut("TEST SHORTCUT 2")]
	#[SimpleForAllAttribute]
	static protected function staticProtectedMethod15() {

	}

	#[SimpleLimitedAttribute]
	#[SimpleForAllAttribute]
	static private function staticPrivateMethod16() {

	}

}
