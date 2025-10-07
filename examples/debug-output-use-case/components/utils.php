<?php


/**
 * Return value as `string`
 *
 * syntactic sugar for "{$val}"
 *
 * @param mixed $val
 * @return string
 */
function str(mixed $val): string {
	return "{$val}";
}

/**
 * Return Debug String
 *
 * @param mixed $val
 * @return string
 */
function debug(mixed $val): string {
	return print_r($val, true);
}

/**
 * Output any argument as a single string with \n in the end
 *
 * @param ...$args
 * @return void
 */
function out(...$args): void {
	foreach ($args as $arg) {
		print_r($arg);
	}
	echo "\n";
}
