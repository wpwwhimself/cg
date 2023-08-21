<?php
session_start();
require("cue.php");
giveMeTheCue(1);

//kwerenda tytułu
$q = "SELECT tytuł FROM piosnki WHERE id=".$_GET['id'];
$r = $conn->query($q) or die($conn->error);
$a = $r->fetch_assoc();
$r->free_result();

cgHead($a['tytuł']." | Dodaj tekst");
?>
<h1>Kreator tekstu</h1>
<h2><a href="core/lyrics.php?id=<?php echo $_GET['id']; ?>"><?php echo $a['tytuł']; ?></a></h2>
<a href="insert.php?id=<?php echo $_GET['id']; ?>">Edytuj metadane...</a>
<hr>
<?php
########## faza pierwsza
if($_GET['phase'] == 1){ ?>
<h3>Krok 1. – dodaj tekst</h3>
<p>W okno poniżej wklej tekst piosenki, rozdzieliwszy poszczególne części podwójnymi enterami.</p>
<form method=post class=insert action="?id=<?php echo $_GET['id']; ?>&phase=2">
<textarea name="linput"></textarea><br>
<input type="submit" name="submit" value="Dalej"></input>
</form>
<?php }

########## faza druga
if($_GET['phase'] == 2){
$lraw = preg_split('/\r\n\r\n|\r\r|\n\n/', $_POST['linput']);
$_SESSION['lraw'] = $lraw;
?>
<form method=post class=insert action="?id=<?php echo $_GET['id']; ?>&phase=3">
<table>
<?php
for($i=0; $i<count($lraw); $i++){
    print "<tr id='r$i'>";
    print "<td>".str_replace(PHP_EOL, "<br>", $lraw[$i])."</td>";
    print "<td>";
    
    print "<input type=radio id='a".$i."t' name='a".$i."' value=1 onchange='greyify(this.value, $i);' checked></input>";
    print "<label for='a".$i."t'>Tekst</label>";
    print "<input type=radio id='a".$i."f' name='a".$i."' value=0 onchange='greyify(this.value, $i);'></input>";
    print "<label for='a".$i."f'>Kreska</label>";
    print "<br>";
    print "<label for='b".$i."'>Etykieta</label>";
    print "<input type=text id='b".$i."' name='b".$i."' onkeyup='redify(this.value, $i);'></input>";
    
    print "</td>";
    print "</tr>";
} ?>
</table>
<script>
    function redify(what, whichrow){
        var initial = what.substring(0,1);
        if(initial == "R"){
            $('#r'+whichrow).addClass("r");
        }else{
            $('#r'+whichrow).removeClass("r");
        }
    }
    function greyify(what, whichrow){
        if(what == 0){
            $('#r'+whichrow).addClass("g");
        }else{
            $('#r'+whichrow).removeClass("g");
        }
    }
</script>
<input type=submit name=submit value="Dalej"></input>
</form>
<?php }

########## faza trzecia
if($_GET['phase'] == 3){

$lcooked = array();
$lraw = $_SESSION['lraw'];

for($i=0; $i<count($lraw); $i++){
    if($_POST['a'.$i]){ //stwórz blok
        array_push($lcooked,
            "$".$_POST['b'.$i].PHP_EOL.$lraw[$i]
            );
    }else{ //stwórz linię
        array_push($lcooked,
            "=".$_POST['b'.$i]
            );
    }
}
array_push($lcooked, "#");
$contents = implode(PHP_EOL, $lcooked);

// faktyczny zapis do pliku
$plik = fopen("./lyrics/".$_GET['id'].".txt", "w")
	or die("できないよ");
fwrite($plik, $contents);
fclose($plik);

//frontend
print "Udało się! Tekst do utworu „".$a['tytuł']."” został dodany.<br>Za chwilę nastąpi przekierowanie do edytora tekstu.</p>";
print "<script>setTimeout(function(){ window.location.href = 'http://cg.audio-z.wpww.pl/lyricseditor.php?id=".$_GET['id']."'; }, 1000);</script>";
}

if($_GET['id'] != 0) print "<a href='lyricseditor.php?id=0' target='_blank'>Samouczek</a><br>";
print "<a href='/'>Do targu...</a>";
cgBottom();
?>