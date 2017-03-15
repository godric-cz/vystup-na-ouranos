<?php

spl_autoload_register(function($jmenoTridy) {
    include __DIR__ . '/' . $jmenoTridy . '.php';
});

if(in_array('--lang=en', $argv)) {
	$texty = __DIR__ . '/../texty-en';
} else {
	$texty = __DIR__ . '/../texty';
}

$pipeline = new Pipeline($texty);
$pipeline->vytvorit();
