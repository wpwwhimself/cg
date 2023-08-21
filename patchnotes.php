<?php
session_start();
require_once("auth.php");

if(isset($_POST['klucz'])){
	if($_POST['klucz'] == BACKEND_PASSWORD) $_SESSION['klucz'] = true;
}

if(!isset($_SESSION['klucz'])){?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<h1>Klucz?</h1>
	<form method=post>
		<input type=password name='klucz' autofocus/>
		<input type=submit value="Do boju!" />
	</form>
<?php }else{

require("cue.php");
giveMeTheCue("1");
cgHead("Czarna gruszka");
?>

<h1><a href=".">Targ czarnych gruszek</a></h1>
<h2>Patch notes</h2>
<hr>

<span id=patchnotes>

<h2>v2.14 [8-11-2021]</h2>
<h3>Targ Kościelny</h3>
<ul>
<li>Bugfix: edytor metadanych w przypadku braku podania banku podawał słowo „null” zamiast faktycznego braku danych.</li>
<li>Bugfix: filtrowanie okazją nie działało.</li>
</ul>
<h3>Ładne Teksty™ Kościelne</h3>
<ul>
<li>Najechanie na nazwę banku pokazuje jego szczegóły. Edytowalne w metadanych.</li>
</ul>

<h2>v2.13</h2>
<h3>Ogólne</h3>
<ul>
<li>Zmodyfikowano zaplecze, co pozwala teraz na bezszwową zmianę imion śpiewających, instrumentów grających itp.</li>
<li>Podczas nawigowania po stronie zapamiętywane są filtry Targu.</li>
</ul>
<h3>Targ</h3>
<ul>
<li>Edytor metadanych wyświetla opis zastosowanych skrótów po najechaniu kursorem.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Bugfix: poprawka w v2.3 pozwalająca na przewijanie tekstów pulpitowych klawiaturą spowodowała, że latające sekcje refrenów wprowadzone w v2.4 miały ograniczony zakres przemieszczania się. Funkcjonalność usunięta – aby przewijać teksty klawiaturą, należy kliknąć na tekst.</li>
</ul>

<h2>v2.12</h2>
<h3>Targ</h3>
<ul>
<li>Teraz można sortować piosenki po „dacie dodania”, a raczej po ich ID.</li>
<li>Poprawka nazw pól w kreatorze wstawiania tekstu. Widać jest niezbyt jasny.</li>
<li>Oddzielono teksty kościelne.</li>
</ul>

<h2>v2.11</h2>
<h3>Targ</h3>
<ul>
<li>Dodano kreator wstawiania tekstu do nowej piosenki. Teraz każdy może stworzyć własną piosenkę!</li>
</ul>

<h2>v2.10</h2>
<h3>Targ</h3>
<ul>
<li>Alfabetyczne dzielenie listy jest teraz domyślnym ustawieniem. W filtrach można zaznaczyć „nie dziel na litery”.</li>
<li>Bugfix: edytor metadanych niepoprawnie kolorował tła przycisków "zatwierdź" i "usuń".</li>
<li>Dodano linki pomiędzy różnymi elementami Targu (teksty, edytory, lista), dla wygody przemieszczania się.</li>
<li>Poprawiono edytor metadanych utworu:</li>
<ul>
    <li>Po dodaniu nowego utworu następuje automatyczne przekierowanie do edytora tekstu tego utworu.</li>
    <li>Po edycji danych istniejącego utworu interfejs nie znika, dzięki czemu można od razu dokonać kolejnych poprawek.</li>
    <li>Po usunięciu utworu następuje automatyczne przekierowanie do listy utworów.</li>
</ul>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Edytor tekstu pokazuje teraz podgląd edytowanego tekstu oraz jego tytuł w nazwie karty.</li>
<li>Redesign Ładnych Tekstów™ dla telefonów – teraz bardziej kompaktowe, więcej informacji pod ręką.</li>
<li>Przycisk zatwierdzenia zmian w edytorze tekstu przeniesiony na górę. Dodano także bardziej intuicyjny wskaźnik potwierdzenia zapisania.</li>
<li>Poprawiono stylistykę małych zapisów nutowych – są krótsze na końcu.</li>
<li>Bugfix: bloki tekstu dzieliły się na zbyt wiele części (prawdopodobnie w wyniku poprzednich poprawek).</li>
</ul>

<h2>v2.9</h2>
<h3>Ogólne</h3>
<ul>
<li>Dodano Otwieracz Imprezowy – ułatwienie dla nielosujących, pozwalające na wybieranie utworów do kolejki na podstawie numerów utworów.</li>
</ul>

<h2>v2.8</h2>
<h3>Ogólne</h3>
<ul>
<li>Dodano widoczną numerację utworów i uporządkowano podkłady wedle tej numeracji. <i>(a przynajmniej tak powinno być...)</i></li>
</ul>

<h2>v2.7</h2>
<h3>Ogólne</h3>
<ul>
<li>Dodano informacje o wykonawcach. Te widoczne są na tekstach oraz w Trybie imprezowym celem wyszukiwania utworów konkretnych artystów.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Znaki pomocy rytmicznej (tj. ♪, ▓ i |) są teraz kolorowane na szaro, celem pomocy w czytaniu.</li>
</ul>
<h3>Tryb imprezowy</h3>
<ul>
<li>Budowniczy kolejek teraz pokazuje kolory instrumentów, dla wygody instrumentalistów.</li>
<li>Wyszukiwarka teraz poprawnie rozpoznaje polskie znaki.</li>
<li>Wyszukiwarka teraz poprawnie reaguje na wielkie i małe litery — czyt. nie zauważa różnicy między nimi.</li>
<li>Drobne zmiany w wymiarach przycisków.</li>
</ul>

<h2>v2.6</h2>
<h3>Targ</h3>
<ul>
<li>Dodano możliwość edycji Ładnych Tekstów™ w przeglądarce, bez pobierania plików.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Uporządkowano przyciski na dole (wróć, pokaż źródło itd.).</li>
</ul>

<h2>v2.5</h2>
<h3>Ładne Teksty™</h3>
<ul>
<li>Dodano zapis nutowy wewnątrz bloków tekstu.</li>
</ul>

<h2>v2.4</h2>
<h3>Targ</h3>
<ul>
<li>Dodano przewidywane czasy trwania utworów.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Sekcje refrenów teraz pozostają przy krawędzi ekranu.</li>
</ul>
<h3>Tryb imprezowy</h3>
<ul>
<li>Budowniczy Kolejek teraz wyświetla przewidywany czas trwania kolejki.</li>
</ul>

<h2>v2.3</h2>
<h3>Targ</h3>
<ul>
<li>Dodano <b>Tryb imprezowy</b>!</li>
<ul>
<li>Filtry odegranych już utworów, loteria i budowniczy kolejek odseparowane od tekstów i edycji parametrów.</li>
<li>Paczki Ładnych Tekstów™ offline również będą zawierać Tryb Imprezowy.</li>
</ul>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Zmodyfikowane teksty pulpitowe – można przewijać klawiaturą bez klikania.</li>
</ul>

<h2>v2.2</h2>
<h3>Targ</h3>
<ul>
<li>Zmieniono adres z &bdquo;audio-z.com.pl<b>/cg</b>&rdquo; na &bdquo;<b>cg.</b>audio-z.com.pl&rdquo;.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Niższe teksty pulpitowe – nie ma potrzeby oddalania.</li>
</ul>

<h2>v2.1</h2>
<h3>Targ</h3>
<ul>
<li>Dodano patch notes!</li>
<li>Filtrowanie instrumentem pokazuje też piosenki, gdzie instrumentu brak.</li>
<li>Licznik piosenek dzieli je na te, które już były, i te, które jeszcze nie.</li>
<li>Loteria podświetla teraz wszystkie poprzednio losowane piosenki.</li>
<li>Dodano budowniczego kolejek.</li>
</ul>
<h3>Ładne Teksty™</h3>
<ul>
<li>Teraz wyglądają inaczej dla każdej szerokości ekranu.</li>
<li>Zmieniona paleta barw.</li>
<li>Poprawki do składni, dla lepszego wsparcia każdej szerokości ekranu</li>
<li>Interpretacja zapisu nutowego ("%") teraz bardziej intuicyjna.</li>
<li>Samouczek zaktualizowany, aby odzwierciedlać zmiany składni.</li>
</ul>

<h2>v2.0</h2>
<ul>
<li>Wersja zwykła i mobilna spięta w jedność.</li>
<li>Ładne Teksty™ dostępne</li>
</ul>

</span>

<br>
<a href="index.php">Nazad...</a>

<?php
$conn->close();
cgBottom();
}
?>