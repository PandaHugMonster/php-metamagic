<?php

namespace spaf\metamagic\traits;

use Attribute;
use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\attributes\magic\Del;
use spaf\metamagic\attributes\magic\Get;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\attributes\magic\IsAssigned;
use spaf\metamagic\attributes\magic\Set;
use spaf\metamagic\attributes\magic\ToString;
use spaf\metamagic\exceptions\MagicBindingException;
use function is_null;

trait MagicMethodsTrait {

	private static function _missingAttributeException($class, $type, $is_static = false) {
		$st = $is_static?"static":"dynamic";
		$exc = new MagicBindingException(
			"#[{$type}] Attribute is not assigned to a {$st} method in the class"
		);
		$exc->magic_method_class = $class;
		$exc->magic_method_type = $type;
		throw $exc;
	}

	function __call(string $name, array $args) {
		$spell = Call::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			$this->_missingAttributeException(
				Call::class,
				MagicBindingException::TYPE_CALL
			);
		}
		return $spell->process($name, $args);
	}

	static function __callStatic(string $name, array $args) {
		$spell = Call::getFirstSpell(self::class, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				Call::class,
				MagicBindingException::TYPE_CALL,
				true,
			);
		}
		return $spell->process($name, $args);
	}

	function __invoke(...$args) {
		$spell = Invoke::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				Invoke::class,
				MagicBindingException::TYPE_INVOKE,
			);
		}
		return $spell->process(...$args);
	}

	function __get(string $name) {
		$spell = Get::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				Get::class,
				MagicBindingException::TYPE_GET,
			);
		}
		return $spell->process($name);
	}

	function __set(string $name, $value): void {
		$spell = Set::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				Set::class,
				MagicBindingException::TYPE_SET,
			);
		}
		$spell->process($name, $value);
	}

	function __isset(string $name): bool {
		$spell = IsAssigned::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				IsAssigned::class,
				MagicBindingException::TYPE_IS_ASSIGNED,
			);
		}
		return $spell->process($name);
	}

	function __unset(string $name): void {
		$spell = Del::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				Del::class,
				MagicBindingException::TYPE_DEL,
			);
		}
		$spell->process($name);
	}

	function __toString(): string {
		$spell = ToString::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				ToString::class,
				MagicBindingException::TYPE_TO_STRING,
			);
		}
		return $spell->process();
	}

	function __clone(): void {
		$spell = CopyShallow::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				CopyShallow::class,
				MagicBindingException::TYPE_COPY_SHALLOW,
			);
		}
		$spell->process();
	}

	function __debugInfo(): ?array {
		$spell = DebugInfo::getFirstSpell($this, Attribute::TARGET_METHOD);
		if (is_null($spell)) {
			static::_missingAttributeException(
				DebugInfo::class,
				MagicBindingException::TYPE_DEBUG_INFO,
			);
		}
		return $spell?->process();
	}
}
