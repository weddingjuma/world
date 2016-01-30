$(function() {

	$(window).scroll(function () {
		$(".navbar-fixed-top").css("left","-"+$(window).scrollLeft()+"px"); 
   	});

function runOnKeys(func) {
var codes = [].slice.call(arguments, 1);
var pressed = {};

document.onkeydown = function(e) {


// ENTER
if (e.keyCode == 13)
{


 var el = $("*:focus");
 var el_class= el.attr("class");

 if(el.hasClass("mention") && !el.hasClass("post-textarea"))
 {
 el.closest("form").submit();
 }
 

 
}

e = e || window.event;

pressed[e.keyCode] = true;

for(var i=0; i<codes.length; i++) {
if (!pressed[codes[i]]) {
return;
}
}

pressed = {};

func();
};

document.onkeyup = function(e) {
e = e || window.event;

delete pressed[e.keyCode];
};

}

runOnKeys(
function() { 

 var el = $("*:focus");
 var el_class= el.attr("id");

if (el_class == "post-textarea"){
 $("#post-form").submit();
}


},
17,
13
);



})

