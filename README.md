# BP
Interaktivní porovnávání vlakových spojů<br />

autor: Jan Uhlíř<br />

Popis souborů:<br />
&nbsp;/:<br />
&nbsp;&nbsp;index.html - kostra aplikace<br />
&nbsp;php/:<br />
&nbsp;&nbsp;getData - vrátí požadovaná data z DB převedená do JSON formátu<br />
&nbsp;&nbsp;getLabelsY - vrátí pole jmen zastávek pro osu Y<br />
&nbsp;&nbsp;getDirection - nastavuje orientaci osy Y (1/-1)<br />
&nbsp;&nbsp;whisperer - vrací výsledky pro našeptávač<br />
&nbsp;js/:<br />
&nbsp;&nbsp;auto - odchytává interakci s formulářem, pomocí AJAX volá whisperer.php<br />
&nbsp;&nbsp;datepicker - obstarává kalendář na prohlížeči Firefox<br />
&nbsp;&nbsp;script - obstarává obsluhu formuláře, nastavení checkboxů a vykreslení grafu<br />
&nbsp;&nbsp;&nbsp;&nbsp;- ppomocí AJAX volá getLabels, getDirection, getData<br />
&nbsp;sql/:<br />
&nbsp;&nbsp;createDB - skript pro vytvoření databáze<br />
