<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <base href="<?=$base?>">
  <style>
    body {
      font-family: Arial;
      font-size: 0.9em;
      padding: 25mm;
      line-height: 140%;
    }
    .zahlavi {
      border-bottom: solid 0.1em #888;
      padding-bottom: 1em;
      margin-bottom: 1em;
    }
    .zahlavi img { float: left; height: 4.1em; margin-right: 0.3em; }
    h1 { font-weight: normal; font-size: 2.6em; text-align: center; margin: 1.2em 0; }
    h2 { font-weight: normal; font-size: 1.7em; text-align: center; margin: 1.2em 0; }
    .tab { border-collapse: collapse; }
    .tab td, th { border: solid 0.1em #000; padding: 1em; }
    .tab th { font-weight: normal; background-color: #ddd; }
    .tab em { font-style: normal; font-size: 0.8em; }
    p { text-align: justify; margin: 2em 0; }
    .podpisy { width: 100%; text-align: center; margin-top: 5em; }
    .podpisy img { position: absolute; width: 10em; margin-left: 6em; margin-top: -5em; }
    .podpisy th { display: none; }
  </style>
</head>
<body>

<div class="zahlavi">
  <img src="logo.png">
  <strong style="font-size: <?=$velikostTitulku ?? '1.8em'?>"><?=$nazev?></strong><br>
  <?=$podnazev?><br>
  <?=$adresa?>
</div>

<?=$htmlObsah?>

</body>
</html>
