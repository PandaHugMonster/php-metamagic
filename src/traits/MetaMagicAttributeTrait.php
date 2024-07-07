<?php

namespace spaf\metamagic\traits;

use Attribute;
use ReflectionClass;
use spaf\metamagic\exceptions\NoCallableAvailable;
use spaf\metamagic\exceptions\SpellNotFound;
use spaf\metamagic\MetaMagic;
use function is_object;

// MARK Optimize and maybe a bit of refactoring

trait MetaMagicAttributeTrait {

	static protected array $meta_magic_cache = [];

	protected function runMethod(
		$entity,
		array $args,
		?callable $filter = null,
	) {

		// MARK Separate concerns of searching and running!
		// MARK Remove caching now

		$attr_class = static::class;
		if (is_object($entity)) {
			// NOTE Dynamic
			$entity_type = "D";
		} else {
			// NOTE Static
			$entity_type = "S";
		}

        if (is_string($entity)) {
            $class_name = $entity;
        } else {
            $class_name = $entity::class;
        }
		$callable = null;
		$spell = MetaMagic::find(
			entity: $entity,
			attr_class: $attr_class,
			attr_targets: Attribute::TARGET_METHOD,
			first: true,
			filter: $filter,
            static: $entity_type == "S",
            dynamic: $entity_type == "D",
		);
		if (!$spell) {
			$self_reflection = new ReflectionClass($attr_class);
			throw new SpellNotFound(
				"#[{$self_reflection->getShortName()}] attribute is not supplied for unknown params."
			);
		}
        if (is_string($entity)) {
            if ($spell->item_reflection->isStatic()) {
                $callable = $spell->item_reflection->getClosure();
            }
        } else {
	        $callable = $spell->item_reflection->getClosure($entity);
        }
        if (empty($callable)) {
            throw new NoCallableAvailable(
                "Callable is not available (most likely dynamic method on class)"
            );
        }
		return $callable(...$args);
	}

}
