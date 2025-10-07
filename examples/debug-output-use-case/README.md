# Debug output use-case

Currently, PHP does not have a very comfortable way to display objects state/content
when compared to out of the box functionality in Python.

For that purpose, this example represents functionality 
for displaying info comfortably, and simplifying certain actions for developers,
while preserving sensitive information.


1. [example.php](example.php) - entrypoint
2. [components](components) - components
    * [DebugHide.php](components/DebugHide.php) - The Attribute to hide value of a field/property
    * [DebuggableTrait.php](components/DebuggableTrait.php) - Logic for `__debugInfo()`
    * [utils.php](components/utils.php) - Simple util functions for turning into string, 
      debug-string and output stuff
    * [SecretToken.php](components/SecretToken.php) - Target class/object to store sensitive information 


The major point of this example is to hide away sensitive fields/properties during debug output,
so no sensitive info would leak into logs, but if the object is turned directly into string - 
then output the ready-to-use token

Entrypoint logic
```php

$token1 = new SecretToken(
	name: "Panda's token",
	secret_username: "Pandator",
	secret_token: "abcd-efgh-ik-22"
);

$token2 = new SecretToken(
	name: "Olga's token",
	secret_token: "1234-5678-90-ab"
);

out("Debug Info: ", debug($token1));
out("Token: ", str($token1));

out("\n\n", "-----", "\n", "\n");

out("Debug Info: ", debug($token2));
out("Token: {$token2}");

```

Output:
```text
Debug Info: SecretToken Object
(
    [name] => Panda's token
    [secret_username] => **hidden-username**
    [secret_token] => ****
)

Token: Pandator@abcd-efgh-ik-22


-----


Debug Info: SecretToken Object
(
    [name] => Olga's token
    [secret_username] => **hidden-username**
    [secret_token] => ****
)

Token: 1234-5678-90-ab

```