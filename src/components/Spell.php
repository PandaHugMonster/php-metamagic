<?php

namespace spaf\metamagic\components;

use ReflectionAttribute;
use Reflector;

/**
 * Spell Wrapper
 *
 * Encapsulates reference to the entity (method, class, etc.) and related attribute(s),
 * so it could be called upon.
 *
 * What essentially turns `Attribute` to "python-alike decorator".
 * Which is only partially similar.
 *
 */
class Spell {

	/**
	 * @param mixed                 $entity
	 * @param Reflector             $item_reflection
	 * @param ReflectionAttribute[] $major_attr_reflections
	 * @param ReflectionAttribute[] $all_attr_reflections
	 */
	function __construct(
		public mixed     $entity,
		public Reflector $item_reflection,
		public array     $major_attr_reflections,
		public array     $all_attr_reflections,
	) {

	}

}
