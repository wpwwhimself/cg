<?php
session_start();
$z = $_SERVER['DOCUMENT_ROOT']; $z .= "/cue.php"; require($z); //podpięcie generatora
giveMeTheCue("1");
require('style.php');

#####

//Pobierz surowicę piosenki
$q = "SELECT id, tytuł, artysta, wokal, instr, tonacja, kanały, tempo, wiek, p.id_singer, p.id_ins, p.id_tempo
	FROM piosnki p
		LEFT JOIN wokal w ON p.id_singer = w.id_singer
		LEFT JOIN instrumenty i ON p.id_ins = i.id_ins
		LEFT JOIN tempo t ON p.id_tempo = t.id_tempo
	WHERE id=".$_GET['id'];
$r = $conn->query($q)
	or die($conn->error);
$songdata = $r->fetch_assoc();
$r->free_result();

//pobierz kolory
$cg_colors = cgGetColors();

#####
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>[<?php echo $songdata['id']; ?>] <?php echo $songdata['tytuł']; ?></title>
<?php cgStyle($cg_colors); ?>
</head>
<body>

<div class="songinfo">
<div class='w<?php echo substr($songdata['id_singer'],0,1); ?>'>
	<span class=title>wokal</span>
	<?php echo $songdata['wokal']; ?>
</div>
<div class='i<?php echo substr($songdata['id_ins'],0,1); ?>'>
	<span class=title>instr.</span>
	<?php echo $songdata['instr']; ?>
</div>
<div>
	<span class=title>numer</span>
	<?php echo $songdata['id']; ?>
</div>
<div>
	<span class=title>tonacja</span>
	<?php echo $songdata['tonacja']; ?>
</div>
<div>
	<span class=title>kanały</span>
	<?php if($songdata['kanały'] !== null) echo $songdata['kanały'];
		else echo "—"; ?>
</div>
<div class='t<?php echo substr($songdata['id_tempo'],0,1); ?>'>
	<span class=title>tempo</span>
	<?php echo $songdata['tempo']; ?>
</div>
<div>
	<span class=title>rok</span>
	<?php switch($songdata['wiek']){
		case 1: echo "trad."; break;
		case 2: echo "dziec."; break;
		case 3: echo "b/d"; break;
		default: echo $songdata['wiek']." r.";
		} ?>
</div>
</div>
<h2><?php echo $songdata['artysta']; ?></h2>
<h1><?php echo $songdata['tytuł']; ?></h1>

<section>
<?php 

##### interpreter #####
global $lyrics;
global $linenotread;
global $blokstarted;
$blokstarted = "broken";

//if($linenotread === undefined) 
$linenotread = false;

$lyrics = fopen("../lyrics/".$_GET['id'].".txt", "r")
	or die("できないよ");

while(!feof($lyrics)){
	if(!$linenotread) $l = purifyLine(fgets($lyrics));
	else $l = $linenotread;
	$linenotread = false;
		
	switch(substr($l,0,1)){
		case "#": break 2;
		case "=": cstrBlkStp(); cstrSep(substr($l, 1)); break;
		case "$": cstrBlk(substr($l, 1)); break;
		case "*": cstrCmt(substr($l, 1)); break;
		case "%"; cstrBlkStp(); cstrStf(substr($l, 1)); break;
		default: cstrCmt($l);
	}
}
cstrBlkStp(); cstrSep("EOF");

fclose($lyrics);

?>
</section>
<script>
    document.getElementsByTagName('SECTION')[0].click();
</script>

<?php if(substr($_SERVER['DOCUMENT_ROOT'],0,3) != "C:/"){ ?>
<hr>
<a href="/lyricseditor.php?id=<?php echo $_GET['id']; ?>&bf=<?php echo $_GET['bf'];?>">Edytuj tekst...</a> • 
<a href="/?<?php echo str_replace("!", "&", $_GET['bf']); ?>">Powrót...</a>
<?php } ?>

</body>
</html>
<?php
$conn->close();
?>