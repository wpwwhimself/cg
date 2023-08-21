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
<h2>Statystyki</h2>
<hr>

<?php
// Zebranie informacji o tym, jakie piosenki mamy

function queryTheShitOutOfThisTable($co, $ile, $co2 = null, $ile2 = null){
	global $conn;
	$q = "SELECT COUNT(id)
		FROM piosnki
		WHERE $co = $ile";
	if($co2 !== null) $q .= " AND $co2 = $ile2";
	$r = $conn->query($q) or die($conn->error());
	$a = $r->fetch_array();
	$r->free_result();
	return $a[0];
}

$songcount['E'] = queryTheShitOutOfThisTable("id_singer", 1);
$songcount['W'] = queryTheShitOutOfThisTable("id_singer", 2);
$songcount['M'] = queryTheShitOutOfThisTable("id_singer", 3);
$songcount['s'] = queryTheShitOutOfThisTable("id_ins", 2);
$songcount['t'] = queryTheShitOutOfThisTable("id_ins", 3);
$songcount['a'] = queryTheShitOutOfThisTable("id_ins", 4);
$songcount['x'] = queryTheShitOutOfThisTable("id_ins", 1);

$songcount['Es'] = queryTheShitOutOfThisTable("id_singer", 1, "id_ins", 2);
$songcount['Et'] = queryTheShitOutOfThisTable("id_singer", 1, "id_ins", 3);
$songcount['Ea'] = queryTheShitOutOfThisTable("id_singer", 1, "id_ins", 4);
$songcount['Ex'] = queryTheShitOutOfThisTable("id_singer", 1, "id_ins", 1);

$songcount['Ws'] = queryTheShitOutOfThisTable("id_singer", 2, "id_ins", 2);
$songcount['Wt'] = queryTheShitOutOfThisTable("id_singer", 2, "id_ins", 3);
$songcount['Wa'] = queryTheShitOutOfThisTable("id_singer", 2, "id_ins", 4);
$songcount['Wx'] = queryTheShitOutOfThisTable("id_singer", 2, "id_ins", 1);

$songcount['Ms'] = queryTheShitOutOfThisTable("id_singer", 3, "id_ins", 2);
$songcount['Mt'] = queryTheShitOutOfThisTable("id_singer", 3, "id_ins", 3);
$songcount['Ma'] = queryTheShitOutOfThisTable("id_singer", 3, "id_ins", 4);
$songcount['Mx'] = queryTheShitOutOfThisTable("id_singer", 3, "id_ins", 1);

$songcount['total'] = $songcount['E'] + $songcount['W'] + $songcount['M'];
?>

<table>
	<tr>
	<td>Piosenek razem<br><?php echo $songcount['total']; ?></td>
	<td>Ewelina<br><?php echo $songcount['E']; ?></td>
	<td>Wojtek<br><?php echo $songcount['W']; ?></td>
	<td>Mateusz<br><?php echo $songcount['M']; ?></td>
	</tr>
	<tr>
	<td>Saksofon<br><?php echo $songcount['s']; ?></td>
	<td><?php echo $songcount['Es']; ?></td>
	<td><?php echo $songcount['Ws']; ?></td>
	<td><?php echo $songcount['Ms']; ?></td>
	</tr>
	<tr>
	<td>Trąbka<br><?php echo $songcount['t']; ?></td>
	<td><?php echo $songcount['Et']; ?></td>
	<td><?php echo $songcount['Wt']; ?></td>
	<td><?php echo $songcount['Mt']; ?></td>
	</tr>
	<tr>
	<td>Akordeon<br><?php echo $songcount['a']; ?></td>
	<td><?php echo $songcount['Ea']; ?></td>
	<td><?php echo $songcount['Wa']; ?></td>
	<td><?php echo $songcount['Ma']; ?></td>
	</tr>
	<tr>
	<td>żaden<br><?php echo $songcount['x']; ?></td>
	<td><?php echo $songcount['Ex']; ?></td>
	<td><?php echo $songcount['Wx']; ?></td>
	<td><?php echo $songcount['Mx']; ?></td>
	</tr>
</table>

<a href="/">Do strony głównej...</a>


<?php
$conn->close();
cgBottom();
}
?>