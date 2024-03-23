<?php

namespace spaf\metamagic\traits;

use Attribute;
use Exception;
use ReflectionClass;
use spaf\metamagic\MetaMagic;
use function is_object;

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
		$cache = &static::$meta_magic_cache["{$entity_type}/{$entity::class}"];
		if (!($cache["callable"] ?? false)) {
			$spell = MetaMagic::find(
				entity: $entity,
				attr_class: $attr_class,
				attr_targets: Attribute::TARGET_METHOD,
				first: true,
				filter: $filter,
			);
			if (!$spell) {
				$self_reflection = new ReflectionClass($attr_class);
				throw new Exception(
					"#[{$self_reflection->getShortName()}] attribute is not supplied."
				);
			}
			$cache["callable"] = $spell->item_reflection->getClosure($entity);
		}
		return $cache["callable"](...$args);
	}

}
