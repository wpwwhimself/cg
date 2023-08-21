<?php
require("cue.php");
giveMeTheCue(1);
cgHead("Wgraj tekst");
?>
<h1>Wgrywajka plików</h1>
<?php
if(isset($_POST['submit'])){
	$target_dir = "lyrics/";
	$target_correctname =
	    (!strpos(basename($_FILES["fileToUpload"]["name"])," ")) ?
	        basename($_FILES["fileToUpload"]["name"]) : substr(basename($_FILES["fileToUpload"]["name"]),0,strpos(basename($_FILES["fileToUpload"]["name"])," ")).".txt";
	$target_file = $target_dir.$target_correctname;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Nie udało się. Spróbuj ponownie.";
	    print "<button onclick='window.history.back();'>&laquo;</button>";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "Plik o nazwie $target_correctname został wgrany.";
	        print "<button onclick='window.history.back();window.history.back();'>&laquo;</button>";
	    } else {
	        echo "Nieoczekiwany błąd. Spróbuj ponownie.";
	        print "<button onclick='window.history.back();'>&laquo;</button>";
	    }
	}
}else{
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Upload" name="submit">
    <?php echo substr("124.txt",0,strpos("124.txt"," ")); ?>
</form>
<?php
}
print "<a href='/'>Do targu...</a>";
cgBottom();
?>
