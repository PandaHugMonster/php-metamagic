<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function is_string;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Call extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		$filter = is_string($entity)
			?fn(Spell $spell) => $spell->is_static
			:null;
        return static::runMethod(
            $entity,
            $args,
            $filter
        );
	}
}
