<?php

namespace spaf\metamagic\traits;

use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\attributes\magic\Del;
use spaf\metamagic\attributes\magic\Deserialize;
use spaf\metamagic\attributes\magic\Get;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\attributes\magic\IsSpecified;
use spaf\metamagic\attributes\magic\Serialize;
use spaf\metamagic\attributes\magic\Set;
use spaf\metamagic\attributes\magic\SetState;
use spaf\metamagic\attributes\magic\ToString;
use spaf\metamagic\attributes\magic\WakeUp;
use spaf\metamagic\exceptions\SpellNotFound;

trait MagicMethodsTrait {

	function __call(string $name, array $args) {
		return Call::process($this, $name, $args);
	}

	static function __callStatic(string $name, array $args) {
		return Call::process(self::class, $name, $args);
	}

	function __invoke(...$args) {
		return Invoke::process($this, ...$args);
	}

//	function __serialize(): array {
////        try {
//            return Serialize::process($this);
////        } catch (SpellNotFound $e) {
////            $array = (array) $entity->__serialize();
////        }
//
//	}

	function __get(string $name) {
		return Get::process($this, $name);
	}

	function __set(string $name, $value): void {
		Set::process($this, $name, $value);
	}

	function __isset(string $name): bool {
		return IsSpecified::process($this, $name);
	}

	function __unset(string $name): void {
		Del::process($this, $name);
	}

	function __wakeup(): void {
		WakeUp::process($this);
	}

	function __unserialize(array $data): void {
//		Deserialize::process($this, $data);
	}

//	function __toString(): string {
//		return ToString::process($this);
//	}

	static function __set_state(array $an_array): object {
//		return SetState::process($an_array);
	}

	function __clone(): void {
		CopyShallow::process($this);
	}

	function __debugInfo(): ?array {
		return DebugInfo::process($this);
	}
}
