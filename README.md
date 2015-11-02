# Výstup na Ouranos

Larp Výstup na Ouranos. Struktura dokumentů:

- `postavy` texty pro jednotlivé postavy a scény
- `postavy/spolecne` kusy textů sdílené napříč dokumenty
- `postavy/sablona` a `postavy/grafika` obsahují podklady pro generování html (z nějž se následně generuje pdf)
- `nastroje` skripty zpracovávající texty a šablony a generující pdf

## Vytvoření pdf

Originální text používá font MS Calibri. Před vygenerováním zkopírovat `calibri.tff`, `calibrib.ttf` a další varianty do `postavy/sablona`. Následně:

```bash
make
```
Výsledný dokument se vytvoří v složce `vystupy`.
