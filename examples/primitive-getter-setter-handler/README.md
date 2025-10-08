# Primitive getter/setter handler

A very primitive example of how
the MetaMagic pico-framework could be used to
implement the simplest getter/setter handlers.


1. [example.php](example.php) - entrypoint
2. [components](components) - components
    * [Getter.php](components/Getter.php) - The Getter Attribute
    * [Setter.php](components/Setter.php) - The Setter Attribute
    * [GetterSetterHandlerTrait.php](components/GetterSetterHandlerTrait.php) - The getter/setter logic trait
    * [MyObject.php](components/MyObject.php) - Target example class


Entrypoint logic
```php

$obj = new MyObject();

print_r(">> Initial \"MyParam1\" value: \"{$obj->MyParam1}\"\n");

$obj->MyParam1 = "test-value-1";

print_r(">> Set \"MyParam1\" value: \"{$obj->MyParam1}\"\n");

print_r(">> Orig \"MyParam2\" value: \"{$obj->MyParam2}\"\n");

$obj->MyParam3 = "test-value-3";

```

Output:
```text
>> Initial "MyParam1" value: ""
>> Set "MyParam1" value: "test-value-1"
>> Orig "MyParam2" value: "const-my-val-2"
!! Fake setting of "test-value-3" into "MyParam3"
```