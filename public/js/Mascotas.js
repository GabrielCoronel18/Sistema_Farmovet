

let formularios = document.querySelectorAll(".ajaxForm");
let resultadosTabla = document.getElementById("resultados");


function obtenerDatos(){

let datos = new FormData;
datos.append("obtener",true);


fetch(window.location,{method:"post", body: datos})
.then(resultados => resultados.json())
.then(result => {
    
    if (result.status === "success") {
        
        resultadosTabla.innerHTML = "";
        
        result.resultados.forEach(function(mascota){
        resultadosTabla.innerHTML += 

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
            <td class="table-light"> <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AntecedentesModal"> Mostrar Antecedentes</button></td>
            <td class="table-light">
            <button class="btn btn-sm btn-success btn-editar" value="${id = mascota.id_mascota}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Editar</button> 
            <button class="btn btn-sm btn-danger btn-eliminar" value="${id = mascota.id_mascota}">Eliminar</button>
            </td>
        </tr>`;
      });
    }
    else if(result.status === "error"){

         resultadosTabla.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros<td></tr>"
    }
});

}

obtenerDatos();


resultadosTabla.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);

        alertEliminar("post", datos, obtenerDatos);
    }
});

