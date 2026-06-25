

let formularioMascotas = document.querySelector(".MascotasForm");
let TablaMascotas = document.getElementById("TablaMascotas");
let TituloModal = document.getElementById("TituloModalMascotas");
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
        
        TablaMascotas.innerHTML = "";
        
        result.resultados.forEach(function(mascota){
        TablaMascotas.innerHTML += 

        `<tr>
            <td class="table-light">${mascota.id_mascota}</td>
            <td class="table-light">${mascota.nombre}</td>
            <td class="table-light">${mascota.edad}</td>
            <td class="table-light">${mascota.sexo}</td>
            <td class="table-light">${mascota.chip}</td>
            <td class="table-light td-large">${mascota.procedencia}</td>
            <td class="table-light">${mascota.fch_nacimiento}</td>
            <td class="table-light">${mascota.nombre_raza}</td>
            <td class="table-light">${mascota.pelaje}</td>
            <td class="table-light">${mascota.cedula_cliente} - ${mascota.nombre_cliente}</td>
            <td class="table-light"> <button type="button" class="btn btn-sm btn-success btn-antecedentes" value="${mascota.id_mascota}" data-bs-toggle="modal" data-bs-target="#AntecedentesModal"> Mostrar Antecedentes</button></td>
            <td class="table-light">
            <button class="btn btn-sm btn-success btn-actualizar" value="${mascota.id_mascota}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button> 
            <button class="btn btn-sm btn-danger btn-eliminar" value="${mascota.id_mascota}">Eliminar</button>
            </td>
        </tr>`;
      });
    }
    else if(result.status === "error"){

         TablaMascotas.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros<td></tr>"
    }
});

}

obtenerDatos();



btnAgregar.addEventListener("click", function(e){
     
     formularioMascotas.reset();

     TituloModal.innerText ="Agregar Nueva Mascota";
     document.getElementById("id_mascota").value = "";

});


TablaMascotas.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-actualizar")) {
       
        e.preventDefault(); 
        TituloModal.innerText = "Actualizar Mascota "
        let id = e.target.value;
        let datos = new FormData();

        datos.append("obtenerMascota", true);
        datos.append("id", id);

        fetch(window.location, {method:"post", body: datos})
        .then(respuesta => respuesta.json())
        .then(resultado => {
            
            let result = resultado.resultado;

              if(resultado.status === "success"){
                  document.getElementById("id_mascota").value = result.id_mascota;
                  document.getElementById("nombre").value = result.nombre;
                  document.getElementById("edad").value = result.edad;
                  document.getElementById("sexo").value = result.sexo;
                  document.getElementById("fch_nacimiento").value = result.fch_nacimiento;
                  document.getElementById("chip").value = result.chip;
                  document.getElementById("id_raza").value = result.id_raza;
                  document.getElementById("pelaje").value = result.pelaje;
                  document.getElementById("cedula_cliente").value = result.cedula_cliente;
                  document.getElementById("procedencia").value = result.procedencia;

              }
              else if(resultado.status === "error"){
                  Swal.fire({title: "Error", text: "Error al obtener el registro", icon: "error"})
                             
              }
        })
    }
});

formularioMascotas.addEventListener("submit", function(e){
      
       e.preventDefault();
       let datos = new FormData(formularioMascotas);
       let id =  document.getElementById("id_mascota").value;

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
                 
                 let ModalAgregar = bootstrap.Modal.getInstance(document.getElementById("ModalAgregar"));
                 ModalAgregar.hide();

                 obtenerDatos();

             }
            else if(resultado.status === "error"){
                id === "" ? alertAgregar("error") : alertActualizar("error");
            }
 
        })

})
TablaMascotas.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);
        alertEliminar("post",datos,obtenerDatos);
    }
});

filtrar.addEventListener("input", function(){
      param = this.value;
      obtenerDatos(param);
});



