<?php

#[HttpController("my-controller")]
class MyController {

	function __construct(
		public $name = null,
	) {}

	#[HttpRoute("route0")]
	#[HttpRoute("route1")]
	public function routeEntrypoint($path) {
		print_r("My Entrypoint route is invoked with path \"{$path}\"\n");
	}

	#[HttpRoute("route2")]
	public function route2($path) {
		print_r("My route number two is invoked with path \"{$path}\"\n");
	}

	#[HttpRoute("route3")]
	public function route3($path) {
		print_r("My route number 3 is invoked with path \"{$path}\"\n");
	}

}
