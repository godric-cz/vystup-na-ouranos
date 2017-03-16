<?php

spl_autoload_register(function($jmenoTridy) {
  include __DIR__ . '/' . $jmenoTridy . '.php';
});

if(in_array('--lang=en', $argv)) {
  $texty = __DIR__ . '/../texty-en';
  $jazyk = 'en-gb';
} else {
  $texty = __DIR__ . '/../texty';
  $jazyk = 'cs';
}

$pipeline = new Pipeline($texty, $jazyk);
$pipeline->vytvorit();
