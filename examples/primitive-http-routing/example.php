<?php

require_once '../../vendor/autoload.php';
require_once 'components/HttpController.php';
require_once 'components/HttpRoute.php';
require_once 'components/utils.php';
require_once 'components/MyController.php';


$controllers = [
	new MyController(),
	new MyController("another-name"),
];


runRoute($controllers, "/another-name/route2");
echo "----------\n";
runRoute($controllers, "/my-controller/route1");
echo "----------\n";
runRoute($controllers, "/another-name/route0");
echo "----------\n";
runRoute($controllers, "/my-controller/route2");
