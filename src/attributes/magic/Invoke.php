<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\exceptions\MethodNotFound;
use spaf\metamagic\exceptions\ObjectNotInvokable;
use spaf\metamagic\exceptions\SpellNotFound;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Invoke extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	static function process($entity, ...$args) {
        try {
            return static::runMethod($entity, $args);
        } catch (SpellNotFound) {
            $class_name = $entity::class;
            throw new ObjectNotInvokable("Object of class \"{$class_name}\" is not invokable");
        }
	}
}
