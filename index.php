<?php
session_start();
require_once("auth.php");

if(isset($_POST['klucz'])){
	$_SESSION['klucz'] = in_array($_POST['klucz'], [BACKEND_PASSWORD, BACKEND_PASSWORD_OBSERVER]);
	$_SESSION['readonly'] = ($_POST['klucz'] == BACKEND_PASSWORD_OBSERVER);
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
<a href="http://cg2.audio-z.wpww.pl">Idź do kościelnego targu</a>
<hr>

<form method=get>
<table>
	<tr>
		<td>nie dziel<br>na litery</td>
		<td>wokal</td>
		<td>instrument</td>
		<td>tempo</td>
		<td>wiek</td>
	</tr>
	<tr>
		<td>
			<input type=checkbox name='sep' <?php if(isset($_GET['sep']) && $_GET['sep'] == "on") print "checked=checked";?>/>
		</td>
		<td><?php cgFilters("wokal"); ?></td>
		<td><?php cgFilters("instrumenty"); ?></td>
		<td><?php cgFilters("tempo"); ?></td>
		<td>
			<input type=radio name='f_wiek_d' value=">=" <?php if(isset($_GET['f_wiek_d']) && $_GET['f_wiek_d'] == ">=") print "checked=checked";?>/>↑<br>
			<input type=number name='f_wiek_a' style="width: 40px;" <?php if(isset($_GET['f_wiek_a'])) print "value='".$_GET['f_wiek_a']."'";?>/><br>
			<input type=radio name='f_wiek_d' value="<=" <?php if(isset($_GET['f_wiek_d']) && $_GET['f_wiek_d'] == "<=") print "checked=checked";?>/>↓
		</td>
	</tr>
	<tr>
		<td>sort:</td>
		<td colspan=4>
			<input type=radio name='sort' value=1 <?php if(isset($_GET['sort']) && $_GET['sort'] == 1) print "checked=checked";?> />tytułem
			<input type=radio name='sort' value=2 <?php if(isset($_GET['sort']) && $_GET['sort'] == 2) print "checked=checked";?> />tempem
			<input type=radio name='sort' value=3 <?php if(isset($_GET['sort']) && $_GET['sort'] == 3) print "checked=checked";?> />wiekiem
			<input type=radio name='sort' value=4 <?php if(isset($_GET['sort']) && $_GET['sort'] == 4) print "checked=checked";?> />dodaniem
		</td>
	</tr>
	<tr>
		<td colspan=5>
			<input type=submit name='filtr' value="Filtruj" style="width:100%;" />
		</td>
	</tr>
</table>
</form>

<hr>Wyświetlam piosenki w liczbie <span class=counterWerfer>X</span> o łącznym czasie trwania ~<span class=timerWerfer>X:XX</span>. <a href="insert.php">Wstaw nową...</a><br>

<table id="mocarna">
<tr>
	<th>tytuł</th>
	<th>tempo</th>
	<th>wiek</th>
	<th>długość</th>
</tr>
<?php
if(isset($_GET['sep'])){
	$Q = @generatorWielkiejKwerendy();
	$r = mysqli_query($conn, $Q)
		or die(mysqli_error($conn));
		
	rzucajWyniki($r);
}else{
	$litery = literowanie($conn);
	
	foreach($litery as $x => $y){
		print "<tr><th colspan=4 class=separator>".$y."</th></tr>"; //separatory
		$Q = generatorWielkiejKwerendy($y);
		$r = $conn->query($Q)
			or die($conn->error);
		rzucajWyniki($r);
	}
}
?>
</table>

<br>
<a href="patchnotes.php">Historia wersji</a> • <a href="/">Do strony głównej...</a>

<script>
$(function(){
$('.counterWerfer').text(<?php echo count($listaid);?>);
$('.timerWerfer').text("<?php
	for($i=0;$i<count($czasyutworow);$i++) $xx += $czasyutworow[$i];
	$xx = [floor($xx/3600), floor(($xx%3600)/60), $xx%60];
	for($i=1; $i<=2; $i++) if($xx[$i]<10) $xx[$i] = "0".$xx[$i];
	echo implode(":", $xx);
?>");

});
</script>
<?php
$conn->close();
cgBottom();
}
?>