
@font-face {
  font-family: 'SomeFont';
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.'/calibri.ttf'))?>);
}

@font-face {
  font-family: 'SomeFont';
  font-weight: bold;
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.'/calibrib.ttf'))?>);
}

@font-face {
  font-family: 'SomeFont';
  font-style: italic;
  src: url(data:font/truetype;charset=utf-8;base64,<?=base64_encode(file_get_contents(__DIR__.'/calibrii.ttf'))?>);
}
