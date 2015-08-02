<?php

require_once __DIR__.'/Parsedown.php';

$parser = new Parsedown();
$converter = escapeshellarg(__DIR__.'/wkhtmltopdf');
$cil = __DIR__.'/../vystupy/postavy.pdf';
$parametry = '--orientation landscape --margin-top 0 --margin-right 0 --margin-bottom 0 --margin-left 0'; // bug: dole bude vždy 0.5mm margin
$nahled = isset($_GET['nahled']);
$pdf = isset($_GET['pdf']);

$postavy = [];

foreach(glob(__DIR__.'/../postavy/*.md') as $f) {

  // vygenerovat html
  $id = basename($f, '.md'); // identifikátor scéna - postava (např. 1a)
  $html = $parser->text(file_get_contents($f));
  $pruh = 'data:image/png;base64,'.base64_encode(file_get_contents(__DIR__.'/../postavy/grafika/'.$id.'.jpg'));
  $sloupceTrida = $nahled ? 'sloupceWeb' : 'sloupceTisk'; // bug: sloupce na tisk fungují jinak jak v prohlížeči a proto je nutné použít hack
  $jmeno = '';
  $html = preg_replace_callback('@<h1>([^<]+)</h1>@', function($m)use(&$jmeno) {
    $jmeno = $m[1];
    return '';
  }, $html);
  ob_start();
  include __DIR__.'/../postavy/sablona/sablona.php';
  $html = ob_get_clean();
  $html = strtr($html, [
    '<!-- novy sloupec -->' => '<div style="-webkit-column-break-before:always"></div>',
  ]);

  // uložit do tmp souboru
  $htmlFile = tempnam(null, null);
  rename($htmlFile, $htmlFile.'.html'); // přípona je nutná kvůli wkhtmltopdf
  $htmlFile .= '.html';
  file_put_contents($htmlFile, $html);
  $postavy[] = $htmlFile;

}

$soubory = implode(' ', array_map('escapeshellarg', $postavy));
$cilSh = escapeshellarg($cil);
if(!$nahled) {
  $volani = PHP_SAPI === 'cli' ? 'system' : 'exec'; // z commandline vypisovat výstup, na webu ne
  $volani("$converter $parametry $soubory $cilSh 2>&1");
}

// úklid
foreach($postavy as $f) unlink($f);

// různé způsoby zobrazení výsledků
if($pdf) {
  header("Content-type:application/pdf");
  readfile($cil);
}

if($nahled) {
  echo $html;
}
