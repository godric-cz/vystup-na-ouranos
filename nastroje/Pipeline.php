<?php

class Pipeline {

  private
    $cil          = __DIR__ . '/../vystupy/postavy.pdf',
    $jazyk        = 'en-gb', // jazyk pro dělení slov
    $slozkaTextu,
    $slozkaSablon = __DIR__ . '/../sablony',
    $slozkaVystupu= __DIR__ . '/../vystupy',
    $wwwRoot      = '../..'; // relativní cesta od vygenerovaných html k rootu

  function __construct($slozkaTextu, $jazyk) {
    $this->slozkaTextu = $slozkaTextu;
    $this->jazyk = $jazyk;
  }

  function vytvorit() {
    $this->vytvoritPostavy();
    $this->vytvoritZpravu();
    $this->uklid();
  }

  function vytvoritPostavy() {

    $parser = new Parsedown();
    $cil = $this->cil;
    $nahled = isset($_GET['nahled']);
    $pdf = isset($_GET['pdf']);
    if($nahled) $this->wwwRoot = '..';

    $postavy = [];
    $postavyJmena = [];

    foreach(array_reverse(glob($this->slozkaTextu . '/postavy/*.md')) as $f) { // průchod od konce, protože pro titulní strany je třeba znát jména postav

      // vygenerovat proměnné pro šablonu a nahradit md speciální výrazy
      $md = file_get_contents($f); // markdown text
      $id = basename($f, '.md'); // identifikátor scéna - postava (např. 1a)
      $idPostavy = substr($id, 1, 1);
      $idSceny = substr($id, 0, 1);
      $pruh = $this->pruh($idSceny, $idPostavy);
      $sloupceTrida = $nahled ? 'sloupceWeb' : 'sloupceTisk'; // bug: sloupce na tisk fungují jinak jak v prohlížeči a proto je nutné použít hack
      $jazyk = $this->jazyk;
      $md = strtr($md, [
        '<!-- novy sloupec -->' => '<div style="-webkit-column-break-before:always"></div>',
        '<!-- jmena postav -->' => isset($postavyJmena[$idPostavy]) ? '# '.implode('<br>', array_reverse($postavyJmena[$idPostavy])) : '',
        '<!-- uvod -->' => file_get_contents($this->slozkaTextu . '/postavy-spolecny-uvod.md'),
      ]);

      // odstranění hl. nadpisu a převod do proměnné
      $jmeno = '';
      $md = preg_replace_callback('@^# (.*)$@m', function($m)use(&$jmeno) {
        $jmeno = $m[1];
        return '';
      }, $md);
      $postavyJmena[$idPostavy][] = $jmeno;
      $jmenoTrida = strpos($jmeno, '<br>') ? 'soupis' : '';
      $sablonaDir = $this->wwwRoot . '/sablony/postavy';
      $fontyDir   = $this->wwwRoot . '/sablony/postavy/fonty';

      // přeložit md a použít šablonu
      $html = $parser->text($md);
      ob_start();
      require $this->slozkaSablon . '/postavy/sablona.php';
      $html = ob_get_clean();

      // uložit do tmp souboru
      $htmlFile = $this->tempSoubor($id);
      file_put_contents($htmlFile, $html);
      $postavy[] = $htmlFile;

    }

    $postavy = array_reverse($postavy); // vrácení do dopředného pořadí

    if(!$nahled) {
      if(!is_dir(dirname($cil))) {
        mkdir(dirname($cil));
        if(PHP_SAPI !== 'cli') chmod(dirname($cil), 0777); // zpřístupnit všem, pokud zavoláno přes web
      }
      $this->konvertovat($postavy, $cil, [
        'orientation'   =>  'landscape',
      ]);
    }

    // různé způsoby zobrazení výsledků
    if($pdf) {
      header("Content-type:application/pdf");
      readfile($cil);
    }

    if($nahled) {
      echo $html;
    }

  }

  function vytvoritZpravu() {
    $prekladac = new \Michelf\MarkdownExtra;
    $markdownObsah = @file_get_contents($this->slozkaTextu . '/zprava-z-mereni.md');
    if($markdownObsah === false) {
      echo "notice: nenalezen soubor zprava-z-mereni.md, přeskakuji\n";
      return;
    }

    // načíst proměnné pro šablonu
    $promenneTextu = extract_front_matter($markdownObsah);
    extract($promenneTextu);
    $base = $this->wwwRoot . '/sablony/zprava/';
    $htmlObsah = $text = $prekladac->transform($markdownObsah);
    $htmlObsah = str_replace_nth('<table', '<table class="tab"', $htmlObsah, 0); // přidat spec. třídy tabulkám
    $htmlObsah = str_replace_nth('<table', '<table class="podpisy"', $htmlObsah, 1);

    // vygenerovat html výstup šablony
    ob_start();
    require $this->slozkaSablon . '/zprava/zprava.php';
    $html = ob_get_clean();

    // uložit do tmp souboru
    $htmlFile = $this->tempSoubor();
    file_put_contents($htmlFile, $html);

    // převést html výstup na pdf
    $this->konvertovat([$htmlFile], $this->slozkaVystupu . '/zprava-z-mereni.pdf', []);
  }

  /**
   * vytvoří zapisovatelnou složku $dir nebo zhavaruje
   */
  private function assertDir($dir) {
    $parent = dirname($dir);
    if(!is_dir($parent)) $this->assertDir($parent); // rekurzivně vytvořit předky, pokud je to potřeba
    if(!is_dir($dir)) mkdir($dir);
    if(!is_dir($dir)) throw new Exception('nepodařilo se vytvořit složku');
  }

  /**
   * Překonvertuje soubory postav zadané v poli na pdf do složky $cil
   */
  private function konvertovat(array $soubory, $cil, $parametry) {
    $windowsOs = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $binarka   = $windowsOs ? 'wkhtmltopdf.exe' : 'wkhtmltopdf';
    $converter = escapeshellarg(__DIR__ . '/' . $binarka);
    $parametry = array_merge([
      // marginy řešit v rámci html
      'margin-top'    =>  0,
      'margin-right'  =>  0,
      'margin-bottom' =>  0, // bug: dole bude vždy 0.5mm margin
      'margin-left'   =>  0,
      // hack na počkání do dokončení js
      'run-script'    =>  "setInterval(function(){ if(document.readyState=='complete') window.status='done'; }, 100)",
      'window-status' =>  'done',
      // bugfix různých velikostí win/linux
      'zoom'          =>  1.33,
    ], $parametry);

    $parametrySh = '';
    foreach($parametry as $jmeno => $hodnota) {
      if($hodnota === null) continue;
      $parametrySh .= ' --' . $jmeno . ' ' . escapeshellarg($hodnota);
    }

    $souborySh = implode(' ', array_map('escapeshellarg', $soubory));
    $cilSh = escapeshellarg($cil);
    $volani = PHP_SAPI === 'cli' ? 'system' : 'exec'; // z commandline vypisovat výstup, na webu ne
    $volani("$converter $parametrySh $souborySh $cilSh 2>&1");
  }

  /**
   * @return string relativní url od vygenerovaných html k obrázku k dané scéně
   * a postavě
   */
  private function pruh($scena, $postava) {
    foreach([
      '/postavy/grafika/' . $scena . $postava . '.jpg', // specifický pruh
      '/postavy/grafika/' . $scena . '.jpg', // obecný pruh k scéně
    ] as $cesta) {
      if(is_file($this->slozkaSablon . $cesta)) return $this->wwwRoot . '/sablony' . $cesta;
    }
    throw new Exception('nenalezen soubor typu 1a.jpg ani 1.jpg potřebný pro pruh na postavě');
  }

  /**
   * @return string cesta k vytvořenému temp souboru
   */
  private function tempSoubor($jmeno = null) {
    $dir = dirname($this->cil) . '/tmp';
    $this->assertDir($dir);
    $jmeno = $jmeno ?: mt_rand();
    $file = $dir . '/' . $jmeno . '.html';  // přípona je nutná kvůli wkhtmltopdf
    touch($file);
    return $file;
  }

  private function uklid() {
    $tmpdir = dirname($this->cil) . '/tmp';
    foreach(glob($tmpdir.'/*') as $f) unlink($f);
    rmdir($tmpdir);
  }

}
