let id_mascota = null;

let TablaAlergias = document.getElementById("TablaAlergias");
let TablaCirugias = document.getElementById("TablaCirugias");
let TablaEnfermedades = document.getElementById("TablaEnfermedades");

let formularioAlergias = document.getElementById("AlergiasForm");
let formularioCirugias = document.getElementById("CirugiasForm");
let formularioEnfermedades = document.getElementById("EnfermedadesForm");



TablaMascotas.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-antecedentes")) {
        e.preventDefault(); 
        
        id_mascota = e.target.value;
        
        obtenerAntecedentes();
    }
});



function obtenerAntecedentes(){
     if (!id_mascota) return;

    let datos = new FormData();
    datos.append("obtenerAntecedentes", true);
    datos.append("id", id_mascota);

fetch(window.location,{method:"post", body: datos})
.then(resultados => resultados.json())
.then(result => {
    
    
    if (result.status === "success") {
        
        TablaAlergias.innerHTML = "";
        TablaCirugias.innerHTML = "";
        TablaEnfermedades.innerHTML = "";

        result.alergias.forEach(function(alergia){
        TablaAlergias.innerHTML += 

        `<tr>
            <td class="table-light">${alergia.nombre_alergia}</td>
            <td class="table-light">${alergia.fecha_deteccion}</td>
            <td class="table-light">
               <button class="btn btn-sm btn-danger btn-eliminar" value="${alergia.id_alergia_mascota}">Eliminar</button>
            </td>
        </tr>`;
      });

      result.cirugias.forEach(function(cirugia){
        TablaCirugias.innerHTML += 

        `<tr>
            <td class="table-light">${cirugia.nombre_cirugia}</td>
            <td class="table-light">${cirugia.fecha_cirugia}</td>
            <td class="table-light">
               <button class="btn btn-sm btn-danger btn-eliminar" value="${cirugia.id_cirugia_previa}">Eliminar</button>
            </td>
        </tr>`;
      });

       result.enfermedades.forEach(function(enfermedad){
        TablaEnfermedades.innerHTML += 

        `<tr>
            <td class="table-light">${enfermedad.nombre}</td>
            <td class="table-light">${enfermedad.fecha_diagnostico}</td>
            <td class="table-light">${enfermedad.estado_enfermedad}</td>
            <td class="table-light">
               <button class="btn btn-sm btn-danger btn-eliminar" value="${enfermedad.id_enfermedad_sufrida}">Eliminar</button>
            </td>
        </tr>`;
      });
    
    }

    else if(result.status === "error"){

         TablaAlergias.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros</td></tr>";
          TablaCirugias.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros</td></tr>";
           TablaEnfermedades.innerHTML = "<tr> <td colspan='12'> Error al obtener los registros</td></tr>";
    }
});

}


formularioAlergias.addEventListener("submit", function(e){
      
       e.preventDefault();
       let datos = new FormData(formularioAlergias);
        datos.append("agregarAlergia", true);
        datos.append("id", id_mascota);
       
       fetch(window.location,{method:"post", body: datos})
       .then(respuesta => respuesta.json())
        .then(resultado => {
             
             if(resultado.status === "success"){
                 
               alertAgregar("success") 
               obtenerAntecedentes();

             }
            else if(resultado.status === "error"){
                alertAgregar("error") 
            }
 
        })

})

formularioCirugias.addEventListener("submit", function(e){
      
       e.preventDefault();
       let datos = new FormData(formularioCirugias);
        datos.append("agregarCirugia", true);
        datos.append("id", id_mascota);
       
       fetch(window.location,{method:"post", body: datos})
       .then(respuesta => respuesta.json())
        .then(resultado => {
             
             if(resultado.status === "success"){
                 
               alertAgregar("success") 
               obtenerAntecedentes();

             }
            else if(resultado.status === "error"){
                alertAgregar("error") 
            }
 
        })

})

formularioEnfermedades.addEventListener("submit", function(e){
      
       e.preventDefault();
       let datos = new FormData(formularioEnfermedades);
        datos.append("agregarEnfermedad", true);
        datos.append("id", id_mascota);
       
       fetch(window.location,{method:"post", body: datos})
       .then(respuesta => respuesta.json())
        .then(resultado => {
             
             if(resultado.status === "success"){
                 
               alertAgregar("success") 
               obtenerAntecedentes();

             }
            else if(resultado.status === "error"){
                alertAgregar("error") 
            }
 
        })

})

TablaAlergias.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminarAlergia", true);
        datos.append("id", id);
        alertEliminar("post",datos,obtenerAntecedentes);
    }
});

TablaCirugias.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminarCirugia", true);
        datos.append("id", id);
        alertEliminar("post",datos,obtenerAntecedentes);
    }
});

TablaEnfermedades.addEventListener("click", function(e) {
  
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminarEnfermedad", true);
        datos.append("id", id);
        alertEliminar("post",datos,obtenerAntecedentes);
    }
});
