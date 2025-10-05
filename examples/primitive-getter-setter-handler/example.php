<?php

require_once '../../vendor/autoload.php';

require_once 'components/Getter.php';
require_once 'components/Setter.php';
require_once 'components/GetterSetterHandlerTrait.php';
require_once 'components/MyObject.php';


$obj = new MyObject();

print_r(">> Initial \"MyParam1\" value: \"{$obj->MyParam1}\"\n");

$obj->MyParam1 = "test-value-1";

print_r(">> Set \"MyParam1\" value: \"{$obj->MyParam1}\"\n");

print_r(">> Orig \"MyParam2\" value: \"{$obj->MyParam2}\"\n");

$obj->MyParam3 = "test-value-3";
