window.onscroll = function() {

    scroll = document.documentElement.scrollTop;
header = document.getElementById("header");
if (scroll > 20) {
    header.classList.add("nav_mod");
}else if(scroll < 20){
    header.classList.remove("nav_mod");

}


var posicion = window.pageYOffset || document.documentElement.scrollTop;
var elemento1 = document.getElementById("icon_heart");
    var elemento2 = document.getElementById("icon_fire");

    elemento1.style.bottom = posicion * 0.1 + "px";
    elemento2.style.top = posicion * 0.1 + "px";


}

document.getElementById("btn_menu").addEventListener("click", mostrar_menu);

menu = document.getElementById("header");
body = document.getElementById("container_all");
nav = document.getElementById("nav");

function mostrar_menu() {

    menu.classList.toggle("move_content");
    body.classList.toggle("move_content");
nav.classList.toggle("move_nav");
}

window.addEventListener("resize", function(){

if(window.innerWidth > 760)
    menu.classList.remove("move_content");
body.classList.remove("move_content");
nav.classList.remove("move_nav");
})



function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
}

function changeFontSize(delta) {
    const textElement = document.getElementById("text-content");
    const style = window.getComputedStyle(textElement, null).getPropertyValue('font-size');
    const currentSize = parseFloat(style);
    textElement.style.fontSize = (currentSize + delta) + 'px';
}


