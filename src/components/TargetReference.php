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
 * to anything you can assign PHP attribute
 *
 * For example:
 * * Class
 * * Class Constant
 * * Field (Property)
 * * Method
 * * Parameter
 */
class TargetReference {

	public string $name {
		get {
			return $this->reflection->name;
		}
	}

	public bool $is_static {
		get {
			return $this->reflection->isStatic();
		}
	}

	/**
	 * @var ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionParameter $reflection
	 * @noinspection PhpDocFieldTypeMismatchInspection
	 */
	public Reflector $reflection;

	public object|null $object = null;

}