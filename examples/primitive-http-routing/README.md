# Primitive HTTP routing


A very primitive example of how
the MetaMagic pico-framework could be used to 
build a primitive http-routing solution.

1. [example.php](example.php) - entrypoint
2. [components](components) - components
   * [HttpController.php](components/HttpController.php) - HTTP Controller attribute
   * [HttpRoute.php](components/HttpRoute.php) - HTTP sub-route attribute
   * [utils.php](components/utils.php) - Utils with the major logic of HTTP routing
   * [MyController.php](components/MyController.php) - Target class for HTTP routing

Entrypoint logic
```php

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

```

Output:
```text
My route number two is invoked with path "/another-name/route2"
----------
My Entrypoint route is invoked with path "/my-controller/route1"
----------
My Entrypoint route is invoked with path "/another-name/route0"
----------
My route number two is invoked with path "/my-controller/route2"
```