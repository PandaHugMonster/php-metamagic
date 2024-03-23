<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use Serializable;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function class_implements;
use function method_exists;
use function spaf\simputils\basic\pd;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Serialize extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	static function process($entity, ...$args) {
//		if ($entity instanceof Serializable) {
//			return $entity->serialize();
//		}
		$array = (array) static::runMethod($entity, $args);
		return $array;
	}
}
