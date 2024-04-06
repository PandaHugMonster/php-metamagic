<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `__clone()` magic method
 */
#[Attribute(Attribute::TARGET_METHOD)]
class CopyShallow extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

    static function process($entity, ...$args) {
        static::runMethod($entity, $args);
    }
}
