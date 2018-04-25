<?php
$content = file_get_contents("content.json");
$json = json_decode($content);

var_dump($json);