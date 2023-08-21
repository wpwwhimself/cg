<?php
require("cue.php");
giveMeTheCue(1);

$q = "SELECT DISTINCT artysta FROM piosnki ORDER BY artysta ASC";
$r = $conn->query($q) or die($conn->error);
while($a = $r->fetch_array()){ $artysty[] = $a[0]; }
$r->free_result();

$q = $_REQUEST["q"];

$hint = "";

if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach($artysty as $name) {
        if (@stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}

echo $hint === "" ? "nie wiem..." : $hint;

?>