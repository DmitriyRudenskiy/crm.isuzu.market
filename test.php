<?php

echo "I work!\n";

$a = gzcompress("123");

var_dump($a);
exit();

try {
    $pdo = new \PDO(
        'mysql:host=127.0.0.1;dbname=docs_isuzu_market',
        'root',
        '123'
    );
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
