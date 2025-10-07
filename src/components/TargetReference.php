<?php

namespace spaf\metamagic\components;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;

/**
 * Target member is a reference object
 * to anything you can assign PHP attribute to
 *
 * For example:
 * * Class
 * * Class Constant
 * * Field/Property
 * * Method
 *
 * Basically aggregation of the relevant info about the target member
 *
 */
class TargetReference {

	/** @var string $name Member's name */
	public string $name {
		get {
			return $this->reflection->name;
		}
	}

	/** @var bool $is_static Flag whether member is static or not */
	public bool $is_static {
		get {
			return $this->reflection->isStatic();
		}
	}

	/**
	 * Reflection object of the member
	 *
	 * @var ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionParameter|null $reflection
	 * @noinspection PhpDocFieldTypeMismatchInspection
	 */
	public Reflector|null $reflection;

	/** @var object|null $object Target object of the member if applicable (in case search of on object) */
	public object|null $object = null;

}