<?php

namespace spaf\metamagic\components;

use ReflectionAttribute;
use ReflectionMethod;
use Reflector;
use function print_r;
use function var_dump;

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

	public string $name;

	/**
	 * @var string|object|null Either object reference, or class reference
	 */
	public string|object|null $entity;

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
		$this->entity = $object;

        if (is_null($this->entity)) {
	        $this->entity = $class;
	        $this->is_static = true;
        }

		$this->item_reflection = $item_reflection;
		$this->attr_reflections = $attr_reflections;
		$this->name = $item_reflection->getName();
	}

	function process(...$args) {
		// TODO Consider caching in the future for the created instances
		$attr_instance = $this->attr_reflections[0]->newInstance();
		return $attr_instance->process($this, ...$args);
	}

}
