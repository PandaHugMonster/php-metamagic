<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\exceptions\MethodNotFound;
use spaf\metamagic\exceptions\SpellNotFound;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function is_string;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Call extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	static function process($entity, ...$args) {
		$filter = is_string($entity)
			?fn(Spell $spell) => $spell->is_static
			:null;
        try {
            return static::runMethod(
                $entity,
                $args,
                $filter
            );
        } catch (SpellNotFound) {
            if (is_string($entity)) {
                $message = "Static method `{$args[0]}()` is not found";
            } else {
                $message = "Method `{$args[0]}()` is not found";
            }
            throw new MethodNotFound($message);
        }
	}
}
