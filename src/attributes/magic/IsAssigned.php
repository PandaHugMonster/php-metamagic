<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `__isset()` magic method
 */
#[Attribute(Attribute::TARGET_METHOD)]
class IsAssigned extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	static function process($entity, ...$args) {
		return static::runMethod($entity, $args);
	}
}
