<?php
# ========================= Ładność i działalność ==========================
function cgHead($title){
	//początek
	print '<html lang=pl><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	
	//tytuł strony
	print "<title>$title | Audio Z</title>";
	
	//metadata
echo<<<CHUJ
	<meta name="author" content="Wojciech Przybyła, Wesoły Wojownik">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
CHUJ;

	//css'y
	print "<link rel=stylesheet type='text/css' href='/cg.css?".time()."'>";
	
	//css: dynamiczne kolory — pobierz wartości
	$cg_colors = cgGetColors();
	print "<style>";
	foreach($cg_colors as $x => $y){
		if($y === null) continue;
		if($x[0] == "i") print "td.$x{ background: linear-gradient(to right, #00000000 30%, #$y); }\n";
		if($x[0] == "w") print "td.$x::first-letter{color: #$y;}\n";
		if($x[0] == "t") print ".$x{color: #$y;}\n";
		print "span.$x{ color: #$y; }\n";
	}
	print "</style>";
	
	//jQuery
	print '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
	
	//ikona
	print "<link rel=icon type='image/png' href='/footlogo.png'>";
	
	//koniec głowy, początek ciała
	print "</head><body>";
}
function cgBottom(){
	//stopka
	print "<footer>";
	print "Copyright ©2017-".date('Y')." | Gemacht by <a href=\"http://wpww.pl\">Wojciech Przybyła, Wesoły Wojownik</a> himself";
	print "<br><img src=\"/footlogo.png\" alt=\"logo\">";
	print "</footer>";
	//skrypty
	print "<script src='/cg.js?".time()."'></script>";
	//koniec dokumentu
	print "</body></html>";
}
function cgGetColors(){
	global $conn;
	
	$q = "SELECT id_ins, kolor
		FROM instrumenty";
	$r = $conn->query($q)
		or die($conn->error);
	while($a = $r->fetch_array()) $wp_kolor['i'.substr($a[0],0,1)] = $a[1];
	$r->free_result();
	
	$q = "SELECT id_singer, kolor
		FROM wokal";
	$r = $conn->query($q)
		or die($conn->error);
	while($a = $r->fetch_array()) $wp_kolor['w'.substr($a[0],0,1)] = $a[1];
	$r->free_result();

	$q = "SELECT id_tempo, kolor
		FROM tempo";
	$r = $conn->query($q)
		or die($conn->error);
	while($a = $r->fetch_array()) $wp_kolor['t'.substr($a[0],0,1)] = $a[1];
	$r->free_result();
	
	return $wp_kolor;
}
//cgStyle wywołuje się w pliku style.php, jest inwokowane przez pliki, które tego potrzebują

# ========================= CG ==========================
function before(){ //kodowanie stanu filtrowania listy na potrzeby nawigacji
    $i = 0;
    foreach($_GET as $name => $val){ $ret[$i++] = $name."=".$val; }
    return ($ret == "") ? "" : implode("!", $ret);
}
function rzucajWyniki($r){
	global $listaid, $czasyutworow;
	while($a = $r->fetch_assoc()){
		print "<tr id='r".$a['id']."'>";
		$listaid[] = $a['id']; //na potrzeby liczenia utworów
		
		//tytuł
		print "<td class='title w".substr($a['id_singer'], 0, 1);
		if($a['instr'] != "żaden") print " i".substr($a['id_ins'], 0, 1);
		print "'><a href=\"core\lyrics.php?id=".$a['id']."&bf=".before()."\">".$a['tytuł']."</a>";
		//artysta
		print "<span class='artist'>".$a['artysta']."</span>";
		    //przyciski
    		print "<span class='details'>";
    		print "<a href=\"insert.php?id=".$a['id']."&bf=".before()."\" title='Edytuj \"".$a['tytuł']."\"'> &#9660;</a>"; //edytuj
		print "</span></td>";
		
		//tempo
		print "<td class='t".substr($a['id_tempo'], 0, 1)."'>".$a['tempo']."</td>";
		
		//wiek
		switch($a['wiek']){
    		case 1: $a['wiek'] = "trad."; break;
    	    case 2: $a['wiek'] = "dziec."; break;
    	    case 3: $a['wiek'] = "bd.";
		}
		print "<td>".$a['wiek']."</td>";
		
		//długość
	    if($a['długość'] === null){
	        print "<td>bd.</td>";
	        $tis = 4*60;
	    }else{
	        print "<td>".substr($a['długość'], 4)."</td>";
		    $tis = explode(":", $a['długość']);
		    $tis = $tis[0]*3600 + $tis[1]*60 + $tis[2];
	    }
	    $czasyutworow[] = $tis;
					
		print "</tr>";
	}
	$r->free_result();
}
function generatorWielkiejKwerendy($a = null){
	$Q="SELECT id, tytuł, artysta, wokal, instr, tempo, wiek, długość, p.id_singer, p.id_ins, p.id_tempo
		FROM piosnki p
		LEFT JOIN wokal w ON p.id_singer = w.id_singer
		LEFT JOIN instrumenty i ON p.id_ins = i.id_ins
		LEFT JOIN tempo t ON p.id_tempo = t.id_tempo";

	$Q .= filtrowanie($a);
	$Q .= sortowanie();

	return $Q;
}
function filtrowanie($litera = "AA"){
	$a = array();
	
	//litery - jeśli są, dodatkowy argument
	if($litera != "AA"){
		$a['litera'] = "tytuł like \"".$litera."%\"";
	}
	//interpretacja liczb w ankiecie
	if($_GET['f_wokal'] != 0){
		$a['wokal'] = "p.id_singer = ".$_GET['f_wokal'];
	}
	if($_GET['f_ins'] != 0){
		$a['ins'] = "(p.id_ins = ".$_GET['f_ins'];
		$a['ins'] .= " OR p.id_ins = 1)";
	}
	switch($_GET['f_tempo']){
		case 1: $a['tempo'] = "p.id_tempo = 1"; break;
		case 2: $a['tempo'] = "p.id_tempo = 2"; break;
		case 3: $a['tempo'] = "p.id_tempo = 3"; break;
		case 4: $a['tempo'] = "p.id_tempo = 4"; break;
	}
	if($_GET['f_wiek_a']){
		if(!isset($_GET['f_wiek_d'])) $_GET['f_wiek_d'] = ">=";
		if($_GET['f_wiek_a'] == 2 || $_GET['f_wiek_a'] == 1) $_GET['f_wiek_d'] = "=";
		$a['wiek'] = "wiek ".$_GET['f_wiek_d']." ".$_GET['f_wiek_a'];
	}
	if(!empty($a))
		$b = " WHERE ".implode(" AND ", $a);
	if(!empty($b))
		return $b;
}
function sortowanie(){
	$a = " ORDER BY tytuł";
	if(isset($_GET['sort'])){
		$a = " ORDER BY ";
		switch($_GET['sort']){
			case 1: $a .= "tytuł"; break;
			case 2: $a .= "p.id_tempo DESC, tytuł"; break;
			case 3: $a .= "wiek DESC, tytuł"; break;
			case 4: $a .= "id DESC"; break;
		}
	}
	return $a;
}
function literowanie($conn){
	$b = array();
	$q = "SELECT DISTINCT SUBSTRING(tytuł, 1, 1) x FROM piosnki p ";
	$q .= filtrowanie();
	$q .= " ORDER BY x ASC";
	$r = $conn->query($q) or die($conn->error);
	while($a = $r->fetch_array()){
		$b[] = $a[0];
	}
	$r->free_result();
	return $b;
}
function cgFilters($what){
	if($what == "instrumenty") $voxorins = "f_ins";
	else $voxorins = "f_".$what;
	
	global $conn;
	
	print "<select name='$voxorins'>";
	
	print "<option value=0 ";
	if(isset($_GET[$voxorins]) && $_GET[$voxorins] == 0) print "selected=selected";
	switch($what){
		case "wokal": print ">wszyscy</option>"; break;
		case "instrumenty": print ">każdy</option>"; break;
		case "tempo": print ">każde</option>"; break;
	} 
	
	$localq = "SELECT * FROM $what";
	$r = $conn->query($localq)
		or die($conn->error);
	while($a = $r->fetch_array()){
		if($a[1] == "żaden") continue;
		print "<option value=".$a[0]." ";
		if(isset($_GET[$voxorins]) && $_GET[$voxorins] == $a[0]) print "selected=selected";
		print ">".$a[1]."</option>";
	}
	$r->free_result();
	
	print "</select>";
}

# ========================= LYRICS ==========================
function purifyLine($line){
    return str_replace("\n","",$line);
}
function cstrSep($line){ //konstruktor separatorów
	global $blokstarted;

	if($line == "EOF"){
		$addclass = 'eof';
		$line = "■";
	}else{
		switch(substr($line,0,1)){
			case "$": $addclass = 'soft'; $line = substr($line, 1); break;
			case "+": $addclass = 'sharp'; break;
			case "-": $addclass = 'sharp'; break;
			case "R": $addclass = 'chorus';
		}
	}
	/*****/
	print "<div class='sep";
	if(@$addclass) print " $addclass";
	print "'><hr><span>$line</span></div>";
	/*****/
	if($blokstarted == "emergency") $blokstarted = "broken";
}
function cstrBlk($line){ //konstruktor bloków tekstu
	global $linenotread;
	global $lyrics;
	global $blokstarted;
	$line = purifyLine($line);
	
	//inicjał
	$a = explode(" ", $line);
	$line = (ctype_alnum(substr($a[0],-1)))? $a[0] : substr($a[0],0,-1);
	if(@$a[1]){
		for($i=1; $i<count($a); $i++){
			@$addclass .= $a[$i];
			if($i != count($a)-1) $addclass .= " ";
		}
	}
	/*****/
	if($line != "" || $blokstarted === "broken"){
		if($blokstarted === true) cstrBlkStp();
		print "<div class='blok";
		if(substr($line,0,1) == "R") print " chorus";
		print "'><span class='initial";
		if($line == "") print " void";
		print "'>$line</span>";
	}
	/*****/

	$line = fgets($lyrics);
	//if(substr($line,0,1) != "#") $line = substr($line,0,-1);

	while(!in_array(substr($line,0,1), array("=","#","$","*","%"), true)){
		if(@$content) $content .= "<br>";
		//Multivox
		$m_pos = strpos($line,"@");
		while($m_pos !== false){
			$multivox = substr($line,$m_pos+1,3);
			$line = substr($line,0,$m_pos).cstrMultivox($multivox[0], $multivox[1], $multivox[2]).substr($line,$m_pos+4);
			$m_pos = strpos($line,"@");
		}
		//mini-nuty
		if($m_pos = strpos($line,"%")){
			$line2 = cstrStf(substr($line, $m_pos+1), true); //biały znak dodany, bo staff obcina ostatni znak i blok robi to również
			$line = substr($line, 0, $m_pos);
			$line .= $line2;
		}
		//pomoce rytmiczne
		$line =	str_replace("♪", cstrRhythmAid("♪"), $line);
	    $line = str_replace("▓", cstrRhythmAid("▓"), $line);
		$line = str_replace("|", cstrRhythmAid("|"), $line);
		//podkreślenia komentujące
		if(strpos($line,"*")){
			if(substr($line,-1,1) == "*"){
				$line = "<span class='astrsk'>".$line."</span>";
			}else{			
				$line = substr_replace($line,"<span class='astrsk'>",strpos($line,"*"),1);
				if(strpos($line,"*")){
					$line = str_replace("*","*</span>",$line);
				}else{
					$line .= "*</span>";
				}
			}
		}
		@$content .= $line;
		$line = fgets($lyrics);
		//if(substr($line,0,1) != "#") $line = substr($line,0,-1);
	}
	$linenotread = $line;
	/*****/
	@print "<p class='$addclass'>$content</p>";
	$blokstarted = true;
}
function cstrBlkStp(){
	global $blokstarted;
	
	if($blokstarted === true){
		print "</div>";
		$blokstarted = "emergency";
	}
}
function cstrCmt($line){ //konstruktor komentarzy
	print "<span class='comment'>$line</span>";
}
function cstrMultivox($wok1, $wok2, $wok3){
	$out = "<span class='multivox'>";
	for($i=-1; $i<=3; $i++){
		$i2 = ($i<=0) ? $i+5 : $i;
		$out .= "<span>";
		if($wok1 == $i2) $out .= "<span class='w1'></span>";
		if($wok2 == $i2) $out .= "<span class='w2'></span>";
		if($wok3 == $i2) $out .= "<span class='w3'></span>";
		$out .= "</span>";
	}
	$out .= "</span>";
	return $out;
}
function cstrRhythmAid($content){
    $out = "<span class='comment'>$content</span>";
    return $out;
}
function cstrStf($line, $smallstaff = false){ //konstruktor zapisu nutowego
	$line = substr($line, 0,-1);
	$contenta = explode(",", $line);

	if($smallstaff) array_unshift($contenta, ""); //jeśli smallstaff, to nie ma tytułu, więc trzeba go zrobić sztucznie
	$title = $contenta[0];

	#KOŃFIGURATOR
	$staffscale = ($smallstaff) ? 5 : 10; //smallstaff
	$clefoffset = 4*$staffscale;
	$notesoffset = 1.4*$staffscale;
	$shortoffset = 6;
	
	#owijka
	if(!$smallstaff) $printable = "<div class='notemaker'>";
	
	// tytuł (tylko dla dużych staffów)
	if(!$smallstaff) cstrSep("$".$title);
	
	for($oo=1; $oo<count($contenta); $oo++){
		$notes = $contenta[$oo];

		#LODÓWKA ZMIENNYCH
		$stafflength = strlen($notes) * $notesoffset + $clefoffset - $staffscale - $smallstaff*4;
		$staffheight = 6 * $staffscale;
		$staffrealheight = 5*$staffscale;
		$staffcenter = $staffheight/ 2;
		$noteradius = $staffscale/2;
		$clefx1 = 1.5*$staffscale;
		$clefx2 = 2*$staffscale;
		$clefx3 = 3*$staffscale;
		$clefy1 = 4*$staffscale;
		$clefy2 = 3.5*$staffscale;
		$clefy3 = 2*$staffscale;
		$textheight = $staffscale - 2;
		
		// pięciolinia
		@$printable .= "<svg width=$stafflength height=$staffheight";
		if($smallstaff) $printable .= " class='smallstaff'";
		$printable .= ">";
		$printable .= "<g class='staff'>";
		for($i=$staffscale; $i<$staffscale*6; $i=$i+$staffscale){
			$printable .= "<line x1=0 y1=$i x2=$stafflength y2=$i />";
		}
		$printable .= "</g>";
	
		// klucz
		if(!$smallstaff){ //klucz G, standardowo
			$printable .= "<g class='clef'>";
			$printable .= "<path d='M$clefx1 $clefy1 Q$clefx1 $clefy2 $clefx2 $clefy2 C$clefx3 $clefy2 $clefx3 $staffrealheight $clefx1 $staffrealheight C0 $staffrealheight 0 $clefy3 $clefx3 $clefy3' />";
			$printable .= "</g>";
		}else{ //"klucz C", smallstaff
			$printable .= "<g class='clef'>";
			$printable .= "<path d='M$clefx1 $clefy3 Q$clefx1 $clefx3 $clefx3 $clefx3 Q$clefx1 $clefx3 $clefx1 $clefy1' />";
			$printable .= "</g>";
		}
		
		// nuty
		$noteposition = array(
			"s" => $noteradius,
			"p" => 2*$noteradius,
			"m" => 3*$noteradius,
			"r" => 4*$noteradius,
			"c" => 5*$noteradius,
			"h" => 6*$noteradius,
			"a" => 7*$noteradius,
			"g" => 8*$noteradius,
			"f" => 9*$noteradius,
			"e" => 10*$noteradius,
			"d" => 11*$noteradius
		);
		if($smallstaff) $noteposition = array(
			"l" => $noteradius,
			"s" => 2*$noteradius,
			"p" => 3*$noteradius,
			"m" => 4*$noteradius,
			"r" => 5*$noteradius,
			"c" => 6*$noteradius,
			"h" => 7*$noteradius,
			"a" => 8*$noteradius,
			"g" => 9*$noteradius,
			"f" => 10*$noteradius,
			"e" => 11*$noteradius
		);
		$whereami = $clefoffset;
		for($i=0; $i<strlen($notes); $i++){		
			// nuta
			if(ctype_alpha($notes[$i])){
				$pitch = $noteposition[strtolower($notes[$i])];
				
				$printable .= "<circle class='note";
				if(ctype_upper($notes[$i])) $printable .= " low";
				$printable .= "' cx=$whereami cy=$pitch r=$noteradius />";
			}
			
			//przedłużenie
			if($notes[$i] == "-"){
				//znajdź ostatnią nutę i jej pozycję
				$j = $i;
				$notebefore = $notes[--$j];
				while(!ctype_alpha($notebefore)) $notebefore = $notes[--$j];
				$wherewasi = $whereami - ($notesoffset*($i - $j));
				
				$pitch = $noteposition[strtolower($notes[$j])];
				
				$printable .= "<line class=tied x1=$whereami y1=$pitch x2=$wherewasi y2=$pitch />";
			}
			
			//pauza
			if($notes[$i] == "0"){
				$px1 = $whereami - $noteradius/2;
				$py1 = $staffcenter - $noteradius;
				$px2 = $whereami + $noteradius/2;
				$py2 = $staffcenter + $noteradius;
				$printable .= "<line class=pause x1=$px1 y1=$py1 x2=$px2 y2=$py2 />";
			}
			
			
			//kreska taktowa
			if($notes[$i] == "|"){
				$printable .= "<line class=barline x1=$whereami y1=$staffscale x2=$whereami y2=$staffrealheight />";
			}
			// dwulinia
			if($notes[$i] == "="){
				$x1 = $whereami - $shortoffset/2; $x2 = $whereami + $shortoffset/2;
				$printable .= "<line class=barline x1=$x1 y1=$staffscale x2=$x1 y2=$staffrealheight />";
				$printable .= "<line class=barline x1=$x2 y1=$staffscale x2=$x2 y2=$staffrealheight />";
			}
			
			//dwukropek
			if($notes[$i] == ":"){
				$dotradius = $noteradius / 3;
				$dot1height = $noteposition['c'];
				$dot2height = $noteposition['a'];
				$printable .= "<circle class=barline cx=$whereami cy=$dot1height r=$dotradius />";
				$printable .= "<circle class=barline cx=$whereami cy=$dot2height r=$dotradius />";
			}
			
			//powtórzenie taktu
			if($notes[$i] == "%"){
				$dotradius = $noteradius / 3;
				$left = $whereami - $staffscale/2; $right = $whereami + $staffscale/2;
				$top = $noteposition['r']; $bottom = $noteposition['g'];
				$printable .= "<line class=note x1=$left y1=$bottom x2=$right y2=$top />";
				$printable .= "<circle class=note cx=$left cy=$top r=$dotradius />";
				$printable .= "<circle class=note cx=$right cy=$bottom r=$dotradius />";
			}
			
			//ad libitum
			if($notes[$i] == "*"){
				$up = $staffcenter - $staffscale;
				$down = $staffcenter + $staffscale;
				$left = $whereami - $staffscale/2;
				$right = $whereami + $staffscale/2;
				$printable .= "<path class=barline d='M$whereami $up L$left $staffcenter L$whereami $down L$right $staffcenter L$whereami $up' fill='white' />";
			}
			
			###
			$whereami = $whereami + $notesoffset;
		}
		#####
		$printable .= "</svg>";
	}
	
	if(!$smallstaff) $printable .= "</div>";

	//smallstaff - zwróć czy drukuj?
	if($smallstaff) return $printable; else print $printable;
}
?>