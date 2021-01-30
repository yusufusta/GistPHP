<?php
require __DIR__ . '/../vendor/autoload.php';
$Gist = new YusufUsta\Gist('username', 'token');
print_r($Gist->createGist($Gist->createFile('test.php', '<?php echo "test"; ?>'), "Test PHP File", true));