<?php

require_once __DIR__.'/Parsedown.php';

$parser = new Parsedown();
$converter = escapeshellarg(__DIR__.'/wkhtmltopdf');
$cil = __DIR__.'/../vystupy/postavy.pdf';
$parametry = '--orientation landscape --margin-top 0 --margin-right 0 --margin-bottom 0 --margin-left 0'; // bug: dole bude vždy 0.5mm margin
$nahled = isset($_GET['nahled']);
$pdf = isset($_GET['pdf']);

$postavy = [];
$postavyJmena = [];

foreach(array_reverse(glob(__DIR__.'/../postavy/*.md')) as $f) { // průchod od konce, protože pro titulní strany je třeba znát jména postav

  // vygenerovat proměnné pro šablonu a nahradit md speciální výrazy
  $md = file_get_contents($f); // markdown text
  $id = basename($f, '.md'); // identifikátor scéna - postava (např. 1a)
  $idPostavy = substr($id, 1, 1);
  $idSceny = substr($id, 0, 1);
  $pruh = 'data:image/png;base64,'.base64_encode(file_get_contents(__DIR__.'/../postavy/grafika/'.$id.'.jpg'));
  $sloupceTrida = $nahled ? 'sloupceWeb' : 'sloupceTisk'; // bug: sloupce na tisk fungují jinak jak v prohlížeči a proto je nutné použít hack
  $md = strtr($md, [
    '<!-- novy sloupec -->' => '<div style="-webkit-column-break-before:always"></div>',
    '<!-- jmena postav -->' => isset($postavyJmena[$idPostavy]) ? '# '.implode('<br>', array_reverse($postavyJmena[$idPostavy])) : '',
    '<!-- uvod -->' => file_get_contents(__DIR__.'/../postavy/spolecne/uvod.md'),
  ]);

  // odstranění hl. nadpisu a převod do proměnné
  $jmeno = '';
  $md = preg_replace_callback('@^# (.*)$@m', function($m)use(&$jmeno) {
    $jmeno = $m[1];
    return '';
  }, $md);
  $postavyJmena[$idPostavy][] = $jmeno;
  $jmenoTrida = strpos($jmeno, '<br>') ? 'soupis' : '';

  // přeložit md a použít šablonu
  $html = $parser->text($md);
  ob_start();
  include __DIR__.'/../postavy/sablona/sablona.php';
  $html = ob_get_clean();

  // uložit do tmp souboru
  $htmlFile = tempnam(null, null);
  rename($htmlFile, $htmlFile.'.html'); // přípona je nutná kvůli wkhtmltopdf
  $htmlFile .= '.html';
  file_put_contents($htmlFile, $html);
  $postavy[] = $htmlFile;

}

$postavy = array_reverse($postavy); // vrácení do dopředného pořadí

$soubory = implode(' ', array_map('escapeshellarg', $postavy));
$cilSh = escapeshellarg($cil);
if(!$nahled) {
  if(!is_dir(dirname($cil))) {
    mkdir(dirname($cil));
    if(PHP_SAPI !== 'cli') chmod(dirname($cil), 0777); // zpřístupnit všem, pokud zavoláno přes web
  }
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
