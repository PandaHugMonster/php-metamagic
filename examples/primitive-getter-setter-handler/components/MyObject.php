<?php

/**
 *
 * @property mixed $MyParam1
 * @property-read string $MyParam2
 * @property-write null $MyParam3
 */
class MyObject {
	use GetterSetterHandlerTrait;

	protected $_my_param_1 = null;

	#[Getter]
	protected function getMyParam1() {
		return $this->_my_param_1;
	}

	#[Setter]
	protected function setMyParam1($val) {
		$this->_my_param_1 = $val;
	}

	#[Getter]
	protected function getMyParam2() {
		return "const-my-val-2";
	}

	#[Setter]
	protected function setMyParam3($val) {
		print_r(
			"!! Fake setting of \"{$val}\" into \"MyParam3\"\n"
		);
	}

}
