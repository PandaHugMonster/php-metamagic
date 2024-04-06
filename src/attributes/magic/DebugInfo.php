<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class DebugInfo extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

    static function process($entity, ...$args) {
        return static::runMethod($entity, $args);
    }
}
