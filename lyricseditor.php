<?php
require("cue.php");
giveMeTheCue(1);

//kwerenda tytułu
$q = "SELECT tytuł FROM piosnki WHERE id=".$_GET['id'];
$r = $conn->query($q) or die($conn->error);
$a = $r->fetch_assoc();
$r->free_result();

cgHead($a['tytuł']." | Edytuj tekst");
?>
<h1>Edycja tekstu</h1>
<h2><a href="core/lyrics.php?id=<?php echo $_GET['id']; ?>&bf=<?php echo $_GET['bf']; ?>"><?php echo $a['tytuł']; ?></a></h2>
<a href="insert.php?id=<?php echo $_GET['id']; ?>&bf=<?php echo $_GET['bf']; ?>">Edytuj metadane...</a>
<?php
if(isset($_POST['submit'])){
	$plik = fopen("./lyrics/".$_GET['id'].".txt", "w")
    	or die("できないよ");
    fwrite($plik, $_POST['contents']);
    fclose($plik);
}?>
<form method="post" class="insert">
    <div class="mobilefloat">
        <input type="submit" name="submit" value="Zatwierdź"></input>
        <span class="tick <?php if(isset($_POST['submit'])) print 'ticked'; ?>">&#10004;</span>
    </div>
    <textarea name="contents">
<?php
//zbierz zawartość pliku
$plik = fopen("./lyrics/".$_GET['id'].".txt", "r")
	or die("できないよ");

while(!feof($plik)){
    echo fgets($plik);
}

fclose($plik);
?>
</textarea>
<iframe src="http://cg.audio-z.com.pl/core/lyrics.php?id=<?php echo $_GET['id']; ?>"></iframe>
</form>
<?php
if($_GET['id'] != 0) print "<a href='lyricseditor.php?id=0' target='_blank'>Samouczek</a><br>";
print "<a href='/?".str_replace("!", "&", $_GET['bf'])."'>Do targu...</a>";
cgBottom();
?>
