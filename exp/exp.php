<?php

use exp_classes\GenericAttr1;
use exp_classes\MainInheritor;
use spaf\metamagic\components\Spell;
use spaf\metamagic\MetaMagic;
use function spaf\simputils\basic\pd;

require_once '../vendor/autoload.php';
require_once 'classes.php';



$obj = new MainInheritor;

$res = MetaMagic::find(
	$obj,
	GenericAttr1::class,
	filter: function (Spell $spell) {
		return count($spell->attr_reflections) > 0;
	}
);

pd($res);



//private function getAllTheLastMethodsAndProperties() {
//	$class_reflection = new ReflectionClass($this);
//	$res = [];
//	// Progressing from original class, back to the root classes
//	while ($class_reflection) {
//		$stub = array_merge(
//			$class_reflection->getMethods(),
//			$class_reflection->getProperties()
//		);
//		foreach ($stub as $item) {
//			if (empty($res[$item->getName()])) {
//				$res[$item->getName()] = $item;
//			}
//		}
//		$class_reflection = $class_reflection->getParentClass();
//	}
//	return $res;
//}
