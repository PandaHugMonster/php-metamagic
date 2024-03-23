<?php

use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\traits\MagicMethodsTrait;
use spaf\simputils\attributes\Property;
use spaf\simputils\generic\SimpleObject;
use spaf\simputils\Math;
use spaf\simputils\Str;
use function spaf\simputils\basic\now;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;
use function spaf\simputils\basic\prstr;

require_once 'vendor/autoload.php';


trait GetterSetter {

	function __get(string $name) {
		$name = ucfirst($name);
		$name = "get{$name}";

		return $this->$name();
	}

}


class TestConventionalGetter {
	use GetterSetter;

	protected function getMynose() {
		return "I have taken your nose";
	}
}


$obj = new TestConventionalGetter;

pr($obj->mynose);
