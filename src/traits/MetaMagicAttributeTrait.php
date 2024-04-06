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

	static protected function runMethod($entity, array $args, ?callable $filter = null) {
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
        $s = "{$entity_type}/{$class_name}";
		$ss = &static::$meta_magic_cache;
		$cache = $ss[$s] ?? null;
		if (!($cache["callable"] ?? false)) {
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
                    $cache["callable"] = $spell->item_reflection->getClosure();
                }
            } else {
                $cache["callable"] = $spell->item_reflection->getClosure($entity);
            }
		}
        if (empty($cache["callable"])) {
            throw new NoCallableAvailable(
                "Callable is not available (most likely dynamic method on class)"
            );
        }
		return $cache["callable"](...$args);
	}

}
