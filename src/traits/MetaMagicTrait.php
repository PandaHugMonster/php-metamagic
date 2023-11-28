<?php

namespace spaf\metamagic\traits;

use spaf\metamagic\exceptions\SpellNotImplemented;
use spaf\metamagic\exceptions\SpellNotSupported;
use function array_shift;
use function in_array;
use function is_string;
use function method_exists;
use function str_replace;

/**
 * MetaMagic major trait
 */
trait MetaMagicTrait {

	private static array $supported_spells = [
		'serialize',
		'deserialize',
		'setup',
		'extractFields',
		'withStart',
		'withEnd',
		'l10n',
	];

	/**
	 *
	 * @param string|object $ref
	 * @param               $spell
	 * @param               ...$args
	 *
	 * @return null
	 */
	protected static function metaMagicEntryPoint(string|object $ref, $spell, ...$args) {
		$is_class = is_string($ref) && class_exists($ref, true);

		if (($is_class || is_object($ref)) && method_exists($ref, '_metaMagic')) {
			return $ref::_metaMagic($ref, $spell, ...$args);
		}

		return null;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private static function _spell(mixed ...$spell): mixed {
		$context = $spell[0];
		// MARK remove underscores only from beginning
		$spell_name = str_replace("___", "", $spell[1]);
		$endpoint = "___{$spell_name}";

		array_shift($spell);
		array_shift($spell);

		if (!in_array($spell_name, static::$supported_spells)) {
			throw new SpellNotSupported(
				"The metamagic spell \"{$spell_name}\" is not supported",
			);
		}
		if (!method_exists($context, $endpoint)) {
			throw new SpellNotImplemented(
				"The metamagic spell is supported, but not implemented in the class",
			);
		}

		return $context->$endpoint(...$spell);
	}

}
