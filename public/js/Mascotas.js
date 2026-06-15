

let formularios = document.querySelectorAll(".ajaxForm");
let resultadosTabla = document.getElementById("resultados")
let datos = new FormData;

datos.append("obtener",true);

fetch(window.location,{method:"post",body: datos})
.then(resultados => resultados.json())
.then(result => {
    
    if (datos.status === "success") {
        
        resultadosTabla.innerHTML = "";
        
        result.resultados.forEach(function(mascota){
        resultadosTabla.innerHTML += 

        `<tr>
            <td class="table-light">${mascota.id_mascota}</td>
            <tdclass="table-light">${mascota.nombre}</td>
            <td class="table-light">${mascota.edad}</td>
            <td class="table-light">${mascota.sexo}</td>
            <td class="table-light">${mascota.chip}</td>
            <td class="table-light">${mascota.procedencia}</td>
            <td class="table-light">${mascota.fch_nacimiento}</td>
            <td class="table-light">${mascota.id_raza} - ${mascota.nombre_raza}</td>
            <td class="table-light">${mascota.pelaje}</td>
            <td class="table-light">${mascota.cedula_cliente} - ${mascota.cliente_nombre}}</td>
            <td class="table-light"> <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AntecedentesModal"> Mostrar Antecedentes</button></td>
            <td class="table-light"><button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Editar</button> <button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button></td>
        </tr>`;
      });
    }
});


formularios.forEach(function(form){
     form.addEventListener("submit",function(e){
          e.preventDefault;
     });
});