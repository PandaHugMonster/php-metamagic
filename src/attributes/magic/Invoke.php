<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\exceptions\MethodNotFound;
use spaf\metamagic\exceptions\ObjectNotInvokable;
use spaf\metamagic\exceptions\SpellNotFound;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function print_r;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Invoke extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;
        return static::runMethod($entity, $args);
	}
}
