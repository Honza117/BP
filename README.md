# BP
Interaktivní porovnávání vlakových spojů<br />

autor: Jan Uhlíř<br />

Popis souborů:<br />
  /:<br />
    index.html - kostra aplikace<br />
  php/:<br />
    getData - vrátí požadovaná data z DB převedená do JSON formátu<br />
    getLabelsY - vrátí pole jmen zastávek pro osu Y<br />
    getDirection - nastavuje orientaci osy Y (1/-1)<br />
    whisperer - vrací výsledky pro našeptávač<br />
  js/:<br />
    auto - odchytává interakci s formulářem, pomocí AJAX volá whisperer.php<br />
    datepicker - obstarává kalendář na prohlížeči Firefox<br />
    script - obstarává obsluhu formuláře, nastavení checkboxů a vykreslení grafu<br />
           - ppomocí AJAX volá getLabels, getDirection, getData<br />
  sql/:<br />
    createDB - skript pro vytvoření databáze<br />
