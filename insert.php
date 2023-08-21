<?php
session_start();
require_once("auth.php");

if(isset($_POST['klucz'])){
	if($_POST['klucz'] == BACKEND_PASSWORD) $_SESSION['klucz'] = true;
}

if(!isset($_SESSION['klucz'])){?>
	<h1>Klucz?</h1>
	<form method=post>
		<input type=password name='klucz' autofocus/>
		<input type=submit value="Do boju!" />
	</form>
<?php }else{

require("cue.php");
giveMeTheCue("1");
cgHead("Edytor");

$_SESSION['insert'] = false;

#####TRYB EDYCJI – podanie danych#####
if(isset($_POST['psub'])){
	$_POST['ptytuł'] = str_replace("'", "\'", $_POST['ptytuł']);
	if(isset($_GET['id'])){
		$q = "UPDATE piosnki SET ";
		$q .= "tytuł='".$_POST['ptytuł']."', ";
		$q .= "artysta='".$_POST['partysta']."', ";
		$q .= "tonacja='".$_POST['ptonacja']."', ";
		if($_POST['pkanały'] == "null") $q .= "kanały=null, ";
			else $q .= "kanały='".$_POST['pkanały']."', ";
		$q .= "id_singer=".$_POST['psinger'].", ";
		$q .= "id_ins=".$_POST['pins'].", ";
		$q .= "id_tempo=".$_POST['ptempo'].", ";
		$q .= "wiek=".$_POST['pwiek'].", ";
		$q .= ($_POST['pczas'] == "null") ? "długość=null " : "długość='0:".$_POST['pczas']."' ";
		$q .= "WHERE id=".$_GET['id'];
		$conn->query($q)
			or die($conn->error);
		
		$_SESSION['insert'] = "changed";
		$_SESSION['linktotext'] = $_GET['id'];

	}else{
		$q = "INSERT INTO piosnki VALUES ('', ";
		$q .= "'".$_POST['ptytuł']."', ";
		$q .= "'".$_POST['partysta']."', ";
		$q .= "'".$_POST['ptonacja']."', ";
		if($_POST['pkanały'] == "null") $q .= "null, ";
			else $q .= "'".$_POST['pkanały']."', ";
		$q .= $_POST['psinger'].", ";
		$q .= $_POST['pins'].", ";
		$q .= $_POST['ptempo'].", ";
		$q .= $_POST['pwiek'].", ";
		$q .= ($_POST['pczas'] == "null") ? "null" : "'0:".$_POST['pczas']."'";
		$q .= ")";
		$conn->query($q)
			or die($conn->error);
		
		#dodaj plik Ładnego Tekstu™
		$q = "SELECT id FROM piosnki ORDER BY id DESC LIMIT 1";
		$r = $conn->query($q)
			or die($conn->error);		
		$a = $r->fetch_assoc();
		
		$nowyplik = fopen("lyrics/".$a['id'].".txt", "w");
		fwrite($nowyplik, "Utworzono nową piosenkę ".date('D, d M Y H:i:s')."\n#");
		fclose($nowyplik);
		
		$_SESSION['insert'] = "added";
	}
}
if(isset($_POST['perase2'])){
	$q = "DELETE FROM piosnki WHERE id=".$_GET['id'];
	$conn->query($q)
		or die($conn->error);
		
	#usuń plik Ładnego Tekstu™
	unlink("lyrics/".$_GET['id'].".txt");
	
	$_SESSION['insert'] = "removed";
}

#####TRYB EDYCJI — zebranie danych#####
if(isset($_GET['id'])){
$qq = "SELECT * FROM piosnki WHERE id=".$_GET['id'];
$r = $conn->query($qq)
	or die($conn->error);
$edit = $r->fetch_assoc();
$r->free_result();
$edit['kanały'] = ($edit['kanały']==null) ? "null" : $edit['kanały']; //gdyby był null
$edit['długość'] = ($edit['długość'] == null) ? "null" : substr($edit['długość'], 4);
}

#####DYNAMICZNA ANKIETA#####
function cgFiltersMod($what){ //parametry mogące się zmienić: wokal, instr, tempo
	global $conn;
	global $edit;
	
	switch($what){
		case "instrumenty": $voxorins = "pins"; $varied = $edit['id_ins']; break;
		case "wokal": $voxorins = "psinger"; $varied = $edit['id_singer']; break;
		case "tempo": $voxorins = "ptempo"; $varied = $edit['id_tempo']; break;
	}
	
	$localq = "SELECT * FROM $what";
	$r = $conn->query($localq)
		or die($conn->error);
	while($a = $r->fetch_array()){
		print "<input type=radio name='$voxorins' value=".$a[0]." ";
		if($varied == $a[0]) print "checked";
		if($voxorins == "pins" && $a[0] == 1) print "><span title='".$a[1]."'>Ø";
			else{
				print "><span class='";
				print substr($what,0,1).substr($a[0],0,1);
				print "' title='";
				print $a[1];
				print "'>";
				print strtoupper(substr($a[1],0,1));
				print "</span>";
			}
			
	}
	$r->free_result();
}

?>

<h1><a href=".?<?php echo str_replace("!", "&", $_GET['bf']); ?>">Targ czarnych gruszek</a></h1>
<h2 style="text-align: center; text-indent: 0;"><?php if(isset($_GET['id'])) print "Edytuj"; else print "Wstaw"; ?> piosenkę</h2>
<a href="lyricseditor.php?id=<?php echo $_GET['id']; ?>&bf=<?php echo $_GET['bf']; ?>">Edytuj tekst...</a><br>

<?php
if(isset($_POST['perase'])){
	print "<p>Czy aby na pewno chcesz usunąć ".$edit['tytuł']."?</p>";
	print "<form method=post>";
	print "<button onclick='window.history.back();'>nie</button>";
	print "<input type=submit name='perase2' value='tak'>";
	print "</form>";
}else{
if($_SESSION['insert'] != false){
	print "<h3 style='color: ";
	switch($_SESSION['insert']){
		case "added":
			print "lime'>Dodano! ";
			print "<script>setTimeout(function(){ window.location.href = 'http://cg.audio-z.com.pl/lyricseditor_create.php?id=".$a['id']."&phase=1'; }, 1000);</script>";
			break;
		case "changed":
			print "cyan'>Zmieniono! ";
			break;
		case "removed":
			print "red'>Usunięto! ";
			print "<script>setTimeout(function(){ window.location.href = 'http://cg.audio-z.com.pl'; }, 1000);</script>";
	}
	print "</h3><p style='font-style: italic; color: gray;'>($q)</p>";
}
?>
<form method=post>
<table class="insert" style="width: auto;">
	<?php if(isset($_GET['id'])){?>
	<tr><th style="background-color: red" colspan=2><input type=submit name="perase" value="×" title="Usuń piosenkę" /></th></tr>
	<?php } ?>
<tr>
	<td>Tytuł</td>
	<td><input type=text name="ptytuł" placeholder="Lorem ipsum" value="<?php echo $edit['tytuł']; ?>" autofocus /></td>
</tr>
<tr>
	<td>Wykonawca</td>
	<script>
    function showHint(str) {
        if (str.length == 0) { 
            document.querySelector(".hint").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.querySelector(".hint").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "insert_hint.php?q=" + str, true);
            xmlhttp.send();
        }
    }
    </script>
	<td style="position: relative"><input type=text name="partysta" placeholder="Lorem ipsum" value="<?php echo $edit['artysta']; ?>" onkeyup="showHint(this.value)" /><p class="hint"></p></td>
</tr>
<tr>
	<td>Tonacja</td>
	<td><input type=text name="ptonacja" placeholder="A (C, -3)" value="<?php echo $edit['tonacja']; ?>"/></td>
</tr>
<tr>
	<td>Kanały</td>
	<td><input type=text name="pkanały" placeholder="1, 2 [żaden → 'null']" value="<?php echo $edit['kanały']; ?>"/></td>
</tr>
<tr>
	<td>Kto śpiewa?</td>
	<td><?php cgFiltersMod("wokal");?></td>
</tr>
<tr>
	<td>Co gra?</td>
	<td><?php cgFiltersMod("instrumenty");?></td>
</tr>
<tr>
	<td>Tempo</td>
	<td><?php cgFiltersMod("tempo");?></td>
</tr>
<tr>
	<td>Wiek</td>
	<td><input type=number name="pwiek" placeholder="1997" value="<?php echo $edit['wiek']; ?>"/></td>
</tr>
<tr>
	<td>Czas trwania</td>
	<td><input type=text name="pczas" placeholder="m:s [nie dotyczy → 'null']" value="<?php echo $edit['długość']; ?>"/></td>
</tr>
<tr><th style="background-color: <?php if(isset($_GET['id'])) print "cyan"; else print "lime"; ?>" colspan=2><input type=submit name="psub" value="→" title="Zatwierdź dane" /></th></tr>
</table>
</form>

<?php
}
?>
<a href="/?<?php echo str_replace("!", "&", $_GET['bf']); ?>">Do targu...</a><br>

<?php
$conn->close();
cgBottom();
}
?>