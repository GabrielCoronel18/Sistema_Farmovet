let formularioCliente = document.querySelector(".ClienteForm");
let TablaCliente = document.getElementById("TablaCliente");
let TituloModal = document.getElementById("exampleModalLabel");
let btnAgregar = document.getElementById("btnAgregar");
let filtrar= document.getElementById("filtrar");


function obtenerDatos(param = null){

let datos = new FormData;
datos.append("obtener",true);

if(param != null){
    datos.append("parametro", param);
}

fetch(window.location,{method:"post", body: datos})
.then(resultados => resultados.json())
.then(result => {
    
    if (result.status === "success") {
        
        TablaCliente.innerHTML = "";
        
        result.resultados.forEach(function(cliente){
        TablaCliente.innerHTML += 

        `<tr>
            <td class="table-light">${cliente.cedula_cliente}</td>
            <td class="table-light">${cliente.nombre}</td>
            <td class="table-light">${cliente.apellido}</td>
            <td class="table-light">${cliente.telefono}</td>
            <td class="table-light">${cliente.correo}</td>
            <td class="table-light">${cliente.direccion}</td>
            <td class="table-light">
            <button class="btn btn-sm btn-success btn-actualizar" value="${cliente.cedula_cliente}" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar</button> 
            <button class="btn btn-sm btn-danger btn-eliminar" value="${cliente.cedula_cliente}">Eliminar</button>
            </td>
        </tr>`;
      });
    }
    else if(result.status === "error"){

         TablaCliente.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros<td></tr>"
    }
});

}

obtenerDatos();
console.log("Policia03");


btnAgregar.addEventListener("click", function(e){
     
      formularioCliente.reset();

      TituloModal.innerText ="Agregar Nueva cliente";
      document.getElementById("cedula_cliente").value = "";

 });
console.log("Policia02");

TablaCliente.addEventListener("click", function(e) {
  console.log("Policia06");
    if (e.target.classList.contains("btn-actualizar")) {
       console.log("Policia01");
        e.preventDefault(); 
        TituloModal.innerText = "actualizar cliente "
        let id = e.target.value;
        let datos = new FormData();

        datos.append("obtenerCliente", true);
        datos.append("id", id);

        fetch(window.location, {method:"post", body: datos})
        .then(respuesta => respuesta.json())
        .then(resultado => {
            
            let result = resultado.resultado;

              if(resultado.status === "success"){
                  document.getElementById("cedula_cliente").value = result.cedula_cliente;
                  document.getElementById("nombre").value = result.nombre;
                  
                  document.getElementById("apellido").value = result.apellido;
                  document.getElementById("telefono").value = result.telefono;
                  document.getElementById("correo").value = result.correo;
                  document.getElementById("direccion").value = result.direccion;
              }
              else if(resultado.status === "error"){
                  Swal.fire({title: "Error", text: "Error al obtener el registro", icon: "error"})
                             
              }
        })
    }
});
console.log("Policia04");
formularioCliente.addEventListener("submit", function(e){
      
       e.preventDefault();
       let datos = new FormData(formularioCliente);
       let id =  document.getElementById("cedula_cliente").value;

       if(id === ""){
        datos.append("agregar", true);
       }
       
       else{
        datos.append("actualizar", true);
        datos.append("id", id);
       }

       fetch(window.location,{method:"post", body: datos})
       .then(respuesta => respuesta.json())
        .then(resultado => {
             
             if(resultado.status === "success"){
                 
                id === "" ? alertAgregar("success") : alertActualizar("success");
                 
                 let ModalAgregar = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
                 ModalAgregar.hide();

                 obtenerDatos();

             }
            else if(resultado.status === "error"){
                id === "" ? alertAgregar("error") : alertActualizar("error");
            }
 
        })

})
TablaCliente.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);
        alertEliminar("post",datos,obtenerDatos);
    }
});
console.log("Policia05");
filtrar.addEventListener("input", function(){
      param = this.value;
      obtenerDatos(param);
});
