<?php

use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\traits\MagicAliasTrait;
use spaf\metamagic\traits\MetaMagicTrait;
use spaf\simputils\attributes\markers\Affecting;
use spaf\simputils\attributes\markers\Deprecated;
use spaf\simputils\attributes\markers\Shortcut;
use spaf\simputils\attributes\Property;
use spaf\simputils\generic\SimpleObject;
use spaf\simputils\Math;
use spaf\simputils\Str;
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
	use MagicAliasTrait;

	#[Invoke]
	protected function myPersonalInvoke2($first_arg) {
		prstr("YEEHA 2! {$first_arg}");
	}

}


class TestMagicAliases extends TestMagicAliasesPP {
	use ttt;

	#[Invoke]
	function myPersonalInvoke3($first_arg) {
		prstr("YEEHA 3! {$first_arg}");
	}
//
//	public function __invoke(...$args) {
//		pr("YEEHA 3! {$args[0]}");
//	}

}


class TestMagicAliases2 extends SimpleObject {

	function __invoke($first_arg) {
		prstr("YEEHA 3! {$first_arg}");
	}

	#[Property]
	protected function myPersonalInvoke4() {
		prstr("YEEHA 2! Property");
	}
}

function methodCall($obj) {
	$start = now();
	foreach (Math::range(0, 1000) as $i) {
		$obj->myPersonalInvoke3("Simple Method call {$i}");
		//	$obj("my test {$i}!");
	}
	$delta = now()->diff($start);
	pr("Simple method call delta: {$delta}");
}

function propertyAccess($obj) {
	$start = now();
	foreach (Math::range(0, 1000) as $i) {
		$d = $obj->myPersonalInvoke4;
		//	$obj("my test {$i}!");
	}
	$delta = now()->diff($start);
	pr("Property access delta: {$delta}");
}

function magicMethodCall($obj) {
	$start = now();
	foreach (Math::range(0, 1000) as $i) {
		$obj("Magic Method call {$i}");
	}
	$delta = now()->diff($start);
	pr("Magic Method call delta: {$delta}");
}

function metaMagicCall($obj) {
	$start = now();
	foreach (Math::range(0, 1000) as $i) {
		$obj("MetaMagic call {$i}");
	}
	$delta = now()->diff($start);
	pr("MetaMagic call delta: {$delta}");
}

function metaMagicPseudoCachingCall($obj) {
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

methodCall($obj);

methodCall($obj);

pr(Str::mul("-", 30));

propertyAccess($obj2);
propertyAccess($obj2);

pr(Str::mul("-", 30));

magicMethodCall($obj2);
magicMethodCall($obj2);

pr(Str::mul("-", 30));

metaMagicCall($obj);
metaMagicCall($obj);

pr(Str::mul("-", 30));

metaMagicPseudoCachingCall($obj);
metaMagicPseudoCachingCall($obj);

pd("DONE");
