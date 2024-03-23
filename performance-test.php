<?php

use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\traits\MagicMethodsTrait;
use spaf\simputils\attributes\Property;
use spaf\simputils\generic\SimpleObject;
use spaf\simputils\Math;
use spaf\simputils\Str;
use spaf\simputils\traits\MetaMagic;
use spaf\simputils\traits\SimpleObjectTrait;
use function spaf\simputils\basic\now;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;
use function spaf\simputils\basic\prstr;

require_once 'vendor/autoload.php';

trait ttt {
	#[Invoke]
	protected function myPersonalInvoke1($first_arg) {
		prstr("YEEHA 1! {$first_arg}");
	}
}

class TestMagicAliasesPP {
	use MagicMethodsTrait;

	#[Invoke]
	protected function myPersonalInvoke2($first_arg) {
		return prstr("YEEHA 2! {$first_arg}");
	}

}


class TestMagicAliases extends TestMagicAliasesPP {
	use ttt;

	#[Invoke]
	function myPersonalInvoke3($first_arg) {
		return prstr("YEEHA 3! {$first_arg}");
	}
//
//	public function __invoke(...$args) {
//		pr("YEEHA 3! {$args[0]}");
//	}

}


class TestMagicAliases2 extends SimpleObject {

//	function __invoke($first_arg) {
//		prstr("YEEHA 3! {$first_arg}");
//	}

	#[Property]
	protected function myPersonalInvoke4() {
		echo "YEEHA 2! Property";
//		prstr("YEEHA 2! Property");
	}
}

trait GetterSetter {

	function __get(string $name) {
//		$name = ucfirst($name);
//		$name = "get{$name}";
//
//		return $this->$name();
		return Invoke::process($this);
	}

}

class ffff {
	use MetaMagic;

}

class TestConventionalGetter extends ffff {
	use GetterSetter;

	private $i = 0;

	#[Invoke]
	public function getMynose() {
		$this->i += 1;
		return "I have taken your nose / {$this->i}";
	}
}


$tcg = new TestConventionalGetter;












function tcgTry($obj, $amount) {
	ob_start();
	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		echo "{$obj->mynose} {$i}";
	}
	ob_end_clean();
	$delta = now()->diff($start);
	pr("tcg: {$delta}");
}


function methodCall($obj, $amount) {
	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		$obj->myPersonalInvoke3("Simple Method call {$i}");
		//	$obj("my test {$i}!");
	}
	$delta = now()->diff($start);
	pr("Simple method call delta: {$delta}");
}

function propertyAccess($obj, $amount) {
	ob_start();

	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		$d = $obj->myPersonalInvoke4;
		//	$obj("my test {$i}!");
	}
	$delta = now()->diff($start);
	ob_end_clean();
	pr("Property access delta: {$delta}");
}

function magicMethodCall($obj, $amount) {
	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		$obj("Magic Method call {$i}");
	}
	$delta = now()->diff($start);
	pr("Magic Method call delta: {$delta}");
}

function metaMagicCall($obj, $amount) {
	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		$obj("MetaMagic call {$i}");
	}
	$delta = now()->diff($start);
	pr("MetaMagic call delta: {$delta}");
}

function metaMagicDirectCall($obj, $amount) {
	$start = now();
	foreach (Math::range(0, $amount) as $i) {
		Invoke::process($obj, "MetaMagic direct call {$i}");
//		$obj("MetaMagic call {$i}");
	}
	$delta = now()->diff($start);
	pr("MetaMagic direct call delta: {$delta}");
}

function metaMagicPseudoCachingCall($obj, $amount) {
	/** @var TestMagicAliases $obj */
	$arr = [];

	foreach (Math::range(0, 9) as $a) {
		foreach (Math::range(0, 9) as $b) {
			foreach (Math::range(0, 9) as $c) {
				$arr["test{$a}"]["best{$b}"]["cast{$c}"] = Closure::fromCallable([$obj, "myPersonalInvoke3"]);
			}
		}
	}
//	pr($arr);

	$start = now();
	foreach (Math::range(0, 1000) as $i) {
		$func = $arr["test4"]["best2"]["cast7"];
		$func("MetaMagic (pseudo-caching) call {$i}");
	}
	$delta = now()->diff($start);
	pr("MetaMagic (pseudo-caching) call delta: {$delta}");
}

$obj = new TestMagicAliases;
$obj2 = new TestMagicAliases2;

$amount = 10_000_000;

tcgTry($tcg, $amount);

//methodCall($obj, $amount);
propertyAccess($obj2, $amount);
//metaMagicCall($obj, $amount);
//metaMagicDirectCall($obj, $amount);
//methodCall($obj, $amount);
//magicMethodCall($obj2, $amount);
//metaMagicPseudoCachingCall($obj, $amount);


//pr("", Str::mul("=", 30), "");
//
//propertyAccess($obj2, $amount);
////propertyAccess($obj2, $amount);
//
//pr(Str::mul("-", 30));
//
//metaMagicCall($obj, $amount);
//
//pr(Str::mul("~", 30));
//
//metaMagicDirectCall($obj, $amount);
//
////metaMagicCall($obj, $amount);
//
//pr("", Str::mul("=", 30), "");
//
//methodCall($obj, $amount);
////methodCall($obj, $amount);
//
//pr(Str::mul("-", 30));
//
//magicMethodCall($obj2, $amount);
////magicMethodCall($obj2, $amount);
//
////pr("", Str::mul("=", 30), "");
//
//
////metaMagicPseudoCachingCall($obj, $amount);
////metaMagicPseudoCachingCall($obj, $amount);

pd("DONE");
