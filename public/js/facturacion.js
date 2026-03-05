let carrito = []

function agregarProducto(id,nombre,precio){

let producto = {
id:id,
nombre:nombre,
precio:precio,
cantidad:1
}

carrito.push(producto)

render()

}

function render(){

let tabla = document.getElementById("tablaProductos")
tabla.innerHTML=""

let total = 0

carrito.forEach((p,i)=>{

let subtotal = p.precio * p.cantidad
total += subtotal

tabla.innerHTML += `
<tr>

<td>${p.nombre}</td>

<td>${p.precio}</td>

<td>
<input type="number" value="${p.cantidad}" 
onchange="cambiarCantidad(${i},this.value)">
</td>

<td>${subtotal}</td>

<td>
<button onclick="eliminar(${i})">X</button>
</td>

</tr>
`

})

document.getElementById("total").innerText = total

}

function cambiarCantidad(i,c){

carrito[i].cantidad = c
render()

}

function eliminar(i){

carrito.splice(i,1)
render()

}