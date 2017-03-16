<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style><?php include __DIR__.'/fonty.php' ?></style>
  <script src="<?="$sablonaDir/hyphenator.js"?>"></script>
  <script src="<?="$sablonaDir/$jazyk.js"?>"></script>
  <link  href="<?="$sablonaDir/styl.css"?>" rel="stylesheet">
  <script>
    Hyphenator.config({
      defaultlanguage: '<?=$jazyk?>'
    });
    Hyphenator.run();
  </script>
</head>
<body>

  <div class="stranka">
    <img class="pruh" src="<?=$pruh?>">
    <div class="pismeno <?=$idPostavy?>"></div>
    <h1 class="jmeno <?=$jmenoTrida?>"><?=$jmeno?></h1>
    <div class="text hyphenate">
      <div class="sloupce <?=$sloupceTrida?>">
        <?=$html?>
      </div>
    </div>
  </div>

</body>
</html>
