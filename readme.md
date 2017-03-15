# Výstup na Ouranos

Larp Výstup na Ouranos. Struktura dokumentů:

- `texty/postavy` texty pro jednotlivé postavy a scény
- `texty/postavy-spolecny-uvod.md` kus textu sdílený mezi všechny duše
- `texty-en` obsahují anglický překlad, dokumenty jsou stejné jako v `texty`
- `sablony` obsahují podklady pro generování html (z nějž se následně generuje pdf)
- `nastroje` skripty zpracovávající texty a šablony a generující pdf

## Vytvoření pdf

Originální text používá font MS Calibri. Před vygenerováním zkopírovat `calibri.tff`, `calibrib.ttf` a další varianty do `sablony/postavy/fonty`. Následně:

```bash
make
```
Výsledný dokument se vytvoří v složce `vystupy`.

## TODOs

- v hudbě přejmenovat soubory a uklidit playlisty
- stahování z youtube, pokud lze
- make en
