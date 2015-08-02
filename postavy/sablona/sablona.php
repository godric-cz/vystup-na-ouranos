<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style><?php include __DIR__.'/fonty.php' ?></style>
  <style><?php include __DIR__.'/styl.css' ?></style>
  <script><?php include __DIR__.'/hyphenator.js' ?></script>
  <script><?php include __DIR__.'/cs.js' ?></script>
  <script>
    Hyphenator.config({
      defaultlanguage: 'cs'
    });
    Hyphenator.run();
  </script>
</head>
<body>

  <div class="stranka">
    <img class="pruh" src="<?=$pruh?>">
    <h1 class="jmeno <?=$jmenoTrida?>"><?=$jmeno?></h1>
    <div class="text hyphenate">
      <div class="sloupce <?=$sloupceTrida?>">
        <?=$html?>
      </div>
    </div>
  </div>

</body>
</html>
