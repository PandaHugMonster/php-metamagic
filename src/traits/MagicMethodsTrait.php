<?php

namespace spaf\metamagic\traits;

use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\attributes\magic\Del;
use spaf\metamagic\attributes\magic\Get;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\attributes\magic\IsAssigned;
use spaf\metamagic\attributes\magic\Set;

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

	function __get(string $name) {
		return Get::process($this, $name);
	}

	function __set(string $name, $value): void {
		Set::process($this, $name, $value);
	}

	function __isset(string $name): bool {
		return IsAssigned::process($this, $name);
	}

	function __unset(string $name): void {
		Del::process($this, $name);
	}

//	function __toString(): string {
//		return ToString::process($this);
//	}

	function __clone(): void {
		CopyShallow::process($this);
	}

	function __debugInfo(): ?array {
		return DebugInfo::process($this);
	}
}
