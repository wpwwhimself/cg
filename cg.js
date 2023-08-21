function giveMeTheRandom(){
	/* //usuń klasę randomed
	if(document.getElementsByClassName("randomed")[0] !== undefined){
		document.getElementsByClassName("randomed")[0].className = "";
	} wyrzucone w v2.1 */

	//potrzebności
	var x = document.getElementById("mocarna").getElementsByTagName("TR");
	var possibilities = []; for(var i=0; i<x.length; i++) possibilities.push(x[i]); //zamień listę węzłów na macierz
	for(var i=0; i<possibilities.length; i++){
		if(possibilities[i].id == '' || possibilities[i].className != ''){
			possibilities.splice(i--, 1);
		}
	}
	var range = possibilities.length;
	if(range==0){ //awaryjne zatrzymanie, jeśli nie ma z czego wybierać
		alert("Bęben maszyny losującej jest pusty!");
		return;
	}
	
	//randomizacja
	var target;
	do{
		target = Math.floor(Math.random()*1000);
	}while(target>=range);
	
	//podświetlenie i wywalenie
	var display = $(possibilities[target]).children('.title').html();
	possibilities[target].className = "randomed";
	document.getElementById("randompicker").innerHTML = display;
}

$(function(){

//BUDOWNICZY KOLEJEK
$('.details span').click(function(){
	var whatistherenow = $('#bob').html();
	var whatshouldtherebe = $(this).parent().siblings('a').html();
	$('#bob').html(whatistherenow+"<li>"+whatshouldtherebe+"</li>");
	$(this).parents('tr').addClass("inQ");
});
$('#randompicker').click(function(){
	var whatistherenow = $('#bob').html();
	var whatshouldtherebe = $(this).children('a').html();
	$('#bob').html(whatistherenow+"<li>"+whatshouldtherebe+"</li>");
	//znajdź odpowiednik w tabeli
	var find = $(this).children('a').attr('href');
	$('#mocarna a[href="'+find+'"]').parents('tr').addClass("inQ");
});
$('#budowniczy button').click(function(){
	$('.inQ input[type=checkbox]').prop("checked", true);
	$('input[name=rsub]').css("border", "5px solid lime");
});

});