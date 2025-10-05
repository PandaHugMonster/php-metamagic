<?php

require_once '../../vendor/autoload.php';

require_once 'components/DefaultsAttr.php';
require_once 'components/DefaultsTrait.php';
require_once 'components/MyBio.php';

$bio = new MyBio(
	name: "Panda Doe",
	handle: "PandaHugMonster",
//	age: 35,
);

print_r($bio);