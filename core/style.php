<?php
function cgStyle($cg_colors, $shortened = false){
    echo "<style>";
    if(!$shortened) echo<<<END
body{
	position: relative;
	margin: 0;
	font-size: 25px;
	font-family: Calibri, Arial, sans-serif;
	color: white;
	background-color: #222;
}
h1{
	text-align: center;
	margin: 0; padding: 0 0 0.5em;
	font-size: 2em;
	text-decoration: underline;
}
h2{
    text-align: center;
	margin: 2em 0 0 0; padding: 0.5em 0 0;
	font-size: 1em;
	font-style: italic;
}
END;
    echo<<<END
a:link, a:visited{color: inherit;}
.comment{
	color: gray;
	font-style: italic;
	margin-left: 10px;
}
p .comment{ margin: 0; }
.astrsk{
	text-decoration: underline;
}

.songinfo{
	z-index: 99;
	position: fixed;
	top:0; left:0;
	width: 100%;
	display: flex;
	justify-content: space-between;
	color: black;
	font-size: 0.8em;
}
.songinfo div{
	width: 100%;
	text-align: center;
	padding: 10px 0;
	position: relative;
	background: white;
}
.songinfo .title{
	position: absolute;
	top: 0; left: 0;
	font-size: 0.5em;
	color: gray;
	font-style: oblique;
}

.multivox{
	width: 0.8em; height: 0.8em;
	display: inline-block;
	border-radius: 100%;
	border: 1px solid gray;
	margin: 0 7px;
	overflow: hidden;
	background-color: #222;
	position: relative;
}
.multivox span{
	width: 100%; height: 0.16em;
	display: flex;
}

.sep{
	position: relative;
	color: dimgray;
}
.sep span{
	position: absolute;
	top: -0.5em; left: 0;
	text-align: center;
	display: block;
	width: 100%;
	font-weight: bold;
	color: inherit;
	text-shadow: 0 0 10px gray;
	background: linear-gradient(to right, rgba(255,255,255,0), #222, rgba(255,255,255,0));
}
.sep hr{
	margin: 30px auto;
	width: 80%;
	border: 2px solid gray;
	border-radius: 10px;
}

.blok{
	position: relative;
	margin-bottom: 20px;
}
.initial{
	width: 100px; min-height: 67px;
	font-size: 2.2em;
	text-align: right;
	position: absolute;
	top: 0; left: 0;
	margin: 3px 0;
}
.chorus{position: sticky; top: 44px; z-index: 3; }
.chorus>span{color: red !important;}
.chorus hr{ border: 2px solid red;}
.chorus p{ background-color: #880000CC; box-shadow: 3px 3px 13px #ffffff33; }
.chorus p.x2, .chorus p.x3, .chorus p.x4{ background: linear-gradient(to top left, #740, #880000CC 60%);}
.soft{color: #09f !important;}
.soft hr{ border: 2px solid #09f; width: 60%}
.sharp{color: gold !important;}
.sharp span{ text-shadow: 0 0 5px black; }
.sharp hr{ border: 2px dashed gold;}
.eof{color: lime !important;}
.eof hr{ border: 6px double lime;}
.eof span{ top: -0.25em;}
.blok p{
	margin: 0px 10px 0px 100px; padding: 7px 10px;
	position: relative;
	z-index: 0;
}
p.x2, p.x3, p.x4{ border-right: 3px double orange; background: linear-gradient(to top left, #ffaa0033, #00000000 60%);}
p.x2:after, p.x3:after, p.x4:after{color: #ffaa0077; position: absolute; right: 10px; bottom: 0; font-size: 2em; font-weight: bold; z-index: -1;}
p.x2:after{ content: "×2";}
p.x3:after{ content: "×3";}
p.x4:after{ content: "×4";}
.sep{position: relative; top: initial; z-index: 2;}

/*staff*/
.notemaker{ display: flex; flex-direction: column; align-items: center; }
.notemaker .sep{width: 100%;}
.notemaker .sep hr{margin: 0 auto 30px;}
.staff line{ stroke: gray; stroke-width: 1px; }
.clef line, .clef path{ stroke-width: 2px; stroke:#09f; fill:none;}
.clef line:nth-child(1){ stroke-width: 3px; }
.note{ stroke: white; stroke-width: 1px; fill: white; }
line.note{ stroke-width: 3px; }
.low{ fill:none; }
.barline{ stroke: #09f; stroke-width: 2px; fill: #09f; }
.pause{ stroke: dimgray; stroke-width: 2px; }
.tied{ stroke: white; stroke-width: 4px; stroke-linecap: round; }

.smallstaff{ display: inline; vertical-align: middle; }
.smallstaff .clef line, .smallstaff .clef path{ stroke: #f90; }
.smallstaff .barline{ stroke: #f90; fill: #f90; }
.smallstaff .tied{ stroke-width: 2px; }
.smallstaff .staff line:nth-child(3){ stroke: #f90; }
END;
    if(!$shortened) echo<<<END
/* PULPITOWY */
@media screen and (min-width: 1100px){
	body{ font-size: 20px; }
	h1{ padding: 5px; }
	section{
		white-space: nowrap;
		overflow-x: auto;
	}
	section>.sep{ display: inline-block; }
	.sep, .notemaker .sep{
		width: 80px; height: 460px;
	}
	.sep hr, .notemaker .sep hr{
		width: 350px;
		transform: rotate(90deg);
		transform-origin: 0px 40px;
		margin: 10px auto;
	}
	.sep span{ transform: translate(0, 1em); white-space: normal; }
	.blok, .notemaker{
		display: inline-flex;
		flex-direction: column;
		vertical-align: top;
	}
	.initial{
		position: static;
		text-align: left;
		margin-left: 50px;
	}
	.blok p{
		width: auto;
		margin: 0 10px;
	}
	.chorus{left: 0; top: 0;}
	.notemaker svg{
		display: none;
	}
	.comment{
		/* display: inline-block; usunięte, bo psuje wygląd znaków specjalnych */
		width: 200px;
		white-space: normal;
		vertical-align: top;
	}
}

/* MOBILNY */
@media screen and (max-width: 500px){ 
    body{ font-size: 15px }
    .songinfo{ position: static; }
	.songinfo span{ padding: 3px; }
	h2{ margin-top: 1.2em; }
	.initial{
		display: block; position: absolute; 
		min-height: 40px;
		width: 90%;
		line-height: 80%; text-align: left;
		margin: 0 5%;
		opacity: 0.4;
		font-size: 4em; font-weight: 900; font-style: italic;
		-webkit-text-stroke: 1px black;
	}
    .chorus{ top: 0 ; }
	.void{ display: none; }
	.blok p{
		width: auto;
		margin: 0;
	}
	svg{ transform: scale(0.45); }
	.smallstaff{ transform: unset; }
}
END;
	// kolorki dynamiczne
	foreach($cg_colors as $x => $y){
		if($y === null) continue;
		print "div.$x, span.$x{ background-color: #$y !important; color: black; }\n";
		print "p.$x{ border-left: 3px dashed #$y; }\n";
	}
	print "</style>";
}
?>