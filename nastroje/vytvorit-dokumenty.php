<?php

spl_autoload_register(function($jmenoTridy) {
    include __DIR__ . '/' . $jmenoTridy . '.php';
});

$pipeline = new Pipeline();
$pipeline->vytvorit();
