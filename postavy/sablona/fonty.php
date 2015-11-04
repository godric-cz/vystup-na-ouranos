<?php

if(is_file(__DIR__.'/calibri.ttf')) $font = '/calibri';
else $font = '/carlito';

?>

@font-face {
  font-family: 'SomeFont';
  src: url('<?=$sablonaDir.$font.'.ttf'?>');
}

@font-face {
  font-family: 'SomeFont';
  font-weight: bold;
  src: url('<?=$sablonaDir.$font.'b.ttf'?>');
}

@font-face {
  font-family: 'SomeFont';
  font-style: italic;
  src: url('<?=$sablonaDir.$font.'i.ttf'?>');
}
