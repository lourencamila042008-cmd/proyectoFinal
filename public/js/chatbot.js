function enviar(){

let mensaje = document.getElementById("mensaje").value;

let chat = document.getElementById("chat");

chat.innerHTML += "<p class='usuario'>Tú: " + mensaje + "</p>";

fetch("../../views/Empleado/chat.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"mensaje="+mensaje
})
.then(response => response.text())
.then(data => {

chat.innerHTML += "<p class='bot'>Bot: " + data + "</p>";

document.getElementById("mensaje").value="";

chat.scrollTop = chat.scrollHeight;

})

}


function abrirChat(){
    document.getElementById("chatContainer").style.display="flex";
}

function cerrarChat(){
    document.getElementById("chatContainer").style.display="none";
}

