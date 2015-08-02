<?php

if(is_file(__DIR__.'/calibri.ttf')) $font = '/calibri';
else $font = '/carlito';

?>

@font-face {
  font-family: 'SomeFont';
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.$font.'.ttf'))?>);
}

@font-face {
  font-family: 'SomeFont';
  font-weight: bold;
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.$font.'b.ttf'))?>);
}

@font-face {
  font-family: 'SomeFont';
  font-style: italic;
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.$font.'i.ttf'))?>);
}
