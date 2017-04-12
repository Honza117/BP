# BP
Interaktivní porovnávání vlakových spojů

autor: Jan Uhlíř

Popis souborů:
  /:
    index.html - kostra aplikace
  php/:
    getData - vrátí požadovaná data z DB převedená do JSON formátu
    getLabelsY - vrátí pole jmen zastávek pro osu Y
    getDirection - nastavuje orientaci osy Y (1/-1)
    whisperer - vrací výsledky pro našeptávač
  js/:
    auto - odchytává interakci s formulářem, pomocí AJAX volá whisperer.php
    datepicker - obstarává kalendář na prohlížeči Firefox
    script - obstarává obsluhu formuláře, nastavení checkboxů a vykreslení grafu
           - ppomocí AJAX volá getLabels, getDirection, getData
  sql/:
    createDB - skript pro vytvoření databáze 
