# Marker Analysis


A very primitive example of how
the MetaMagic pico-framework could be used to
analyse "Marker Attributes".

1. [example.php](example.php) - entrypoint with a very basic logic

Output:
```text
Deprecated method found: MyOldCode::obsoleteMethod1()
Deprecation message: I don't like this method 1
--------------
Deprecated method found: MyOldCode::obsoleteMethod3()
Deprecation message: I don't like this method 3
--------------
Deprecated method found: MyOldCode::obsoleteMethod5()
Deprecation message: I don't like this method 5
--------------
```