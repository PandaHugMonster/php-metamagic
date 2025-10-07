<?php

require_once '../../vendor/autoload.php';

require_once 'components/DebugHide.php';
require_once 'components/DebuggableTrait.php';
require_once 'components/utils.php';
require_once 'components/SecretToken.php';


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
