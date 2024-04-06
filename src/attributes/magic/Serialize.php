<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Serialize extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	static function process($entity, ...$args) {
        $array = (array) static::runMethod($entity, $args);
		return $array;
	}
}
