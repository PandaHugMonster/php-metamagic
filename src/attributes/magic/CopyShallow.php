<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `CopyShallow` is triggered when `clone` PHP functionality is invoked on an object.
 *
 * This is a shortcut for `__clone()` PHP magic method.
 *
 * PHP Object Cloning - https://www.php.net/manual/en/language.oop5.cloning.php
 */
#[Attribute(Attribute::TARGET_METHOD)]
class CopyShallow extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

        static::runMethod($entity, $args);
    }
}
