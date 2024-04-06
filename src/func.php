<?php
namespace spaf\metamagic\func;

use spaf\metamagic\internal\MetaMagicSettings;
use spaf\metamagic\attributes\magic\Serialize;

/**
 * MetaMagic implementation of `serialize`
 * @param mixed $value
 * @return string
 */
function serialize(mixed $value): string {
    $callable = MetaMagicSettings::$serialize_func;

    $data = $value;
    if (is_object($value)) {
        $data = Serialize::process($value);
    }

    if (empty($callable)) {
        return \serialize($data);
    }

    return $callable($data);
}
