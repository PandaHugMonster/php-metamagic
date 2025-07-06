<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `DebugInfo` is triggered when `var_dump()` PHP functions is invoked on an object.
 *
 * This is a shortcut for `__debugInfo()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.magic.php#object.debuginfo
 */
#[Attribute(Attribute::TARGET_METHOD)]
class DebugInfo extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

        return static::runMethod($entity, $args);
    }
}
