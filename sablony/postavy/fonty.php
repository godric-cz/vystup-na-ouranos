<?php

if(is_file(__DIR__.'/fonty/calibri.ttf')) $font = '/calibri';
else $font = '/carlito';

?>

@font-face {
  font-family: 'SomeFont';
  src: url('<?=$fontyDir.$font.'.ttf'?>');
}

@font-face {
  font-family: 'SomeFont';
  font-weight: bold;
  src: url('<?=$fontyDir.$font.'b.ttf'?>');
}

@font-face {
  font-family: 'SomeFont';
  font-style: italic;
  src: url('<?=$fontyDir.$font.'i.ttf'?>');
}

@font-face {
  font-family: 'SymbolsFont';
  src: url('<?=$fontyDir.'/LinLibertine_Rah.ttf'?>');
}
