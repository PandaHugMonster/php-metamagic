<?php

namespace spaf\metamagic\components;

use ReflectionAttribute;
use ReflectionMethod;
use Reflector;

/**
 * Spell Wrapper
 *
 * Encapsulates reference to the entity (method, class, etc.) and related attribute(s),
 * so it could be called upon.
 *
 */
class Spell {

	public string $class;

	public ?object $object;

	public Reflector $item_reflection;

	public array $attr_reflections;

    public bool $is_static = false;

	/**
	 * @param string                $class
	 * @param ?object               $object
	 * @param Reflector             $item_reflection
	 * @param ReflectionAttribute[] $attr_reflections
	 */
	function __construct(
		string     $class,
		?object    $object,
		Reflector $item_reflection,
		array     $attr_reflections,
	) {
		/** @var ReflectionMethod $item_reflection */
		$this->class = $class;
		$this->object = $object;
        if (is_null($this->object)) {
            $this->is_static = true;
        }
		$this->item_reflection = $item_reflection;
		$this->attr_reflections = $attr_reflections;
		$this->name = $item_reflection->getName();
	}

}
