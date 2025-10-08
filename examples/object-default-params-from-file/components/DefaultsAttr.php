<?php

namespace components;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DefaultsAttr {

	public string $file_name;

	function __construct(string $file_name) {
		$this->file_name = $file_name;
	}

	function applyOnObject(object $obj): object {
		if (file_exists($this->file_name)) {
			$fd = fopen($this->file_name, "r");
			$content = fread($fd, filesize($this->file_name));
			$params = json_decode($content);
			fclose($fd);

			foreach ($params as $key => $val) {
				$obj->$key = $val;
			}
		}

		return $obj;
	}

}