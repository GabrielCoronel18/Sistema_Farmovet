
function alertAgregar(status){
 
  if(status === "success" ){
  
          Swal.fire({
          title: "Agregado",
          text: "Se ha Agregado Correctamente ",
          icon: "success",
          draggable: true,
          confirmButtonColor: "#18994a",

});
  }
  else if(status === "error"){
          Swal.fire({
          title: "Error",
          text: "Error al Agregar el Registro",
          icon: "error",
          confirmButtonColor: "#ce0808",
        });
  }
}


function alertActualizar(status){
 
  if(status === "success"){
  
          Swal.fire({
          title: "Editado",
          text: "Se ha Editado Correctamente ",
          icon: "success",
          draggable: true,
          confirmButtonColor: "#18994a",

});
  }
  else if(status === "error"){
          Swal.fire({
          title: "Error",
          text: "Error al Editar el Registro",
          icon: "error",
          confirmButtonColor: "#ce0808",
        });
  }
}

function alertEliminar(method, data, callback){

  Swal.fire({
  title: "Confirmar Eliminacion",
  text: "Esta accion no se puede revertir",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#18994a",
  cancelButtonColor: "#d33",
  confirmButtonText: "Confirmar",
  cancelButtonText :"Cancelar",

}).then((result) => {
  if (result.isConfirmed) {
      
      fetch(window.location,{method: method, body: data })
      .then(resultado => resultado.json())
      .then(eliminar => {

        if(eliminar.status === "success"){
          Swal.fire({
            title: "Eliminado",
            text: "El Registro ha Sido Eliminado",
            icon: "success",
            confirmButtonColor: "#18994a",
          }).then(() => {
             if(callback){
              callback();
             }

          }
          );

        }
        else if(eliminar.status === "error"){
            Swal.fire({
            title: "Error",
            text: "Error al eliminar el registro",
            icon: "error",
            confirmButtonColor: "#ce0808",
          });

        }
     
      })
  }
});

}

function alertActualizarCliente(status){
 
  if(status === "success"){
  
          Swal.fire({
          title: "Actualizado",
          text: "Cliente Actualizado exitosamente ",
          icon: "success",
          draggable: true,
          confirmButtonColor: "#18994a",

});
  }
  else if(status === "error"){
          Swal.fire({
          title: "Error",
          text: "Error al Actualizar el Cliente",
          icon: "error",
          confirmButtonColor: "#ce0808",
        });
  }
}

function alertRegistrarCliente(status){
 
  if(status === "success" ){
  
          Swal.fire({
          title: "Registrado",
          text: "Cliente Registrado Exitosamente ",
          icon: "success",
          draggable: true,
          confirmButtonColor: "#18994a",

});
  }
  else if(status === "error"){
          Swal.fire({
          title: "Error",
          text: "Error al registrar el cliente",
          icon: "error",
          confirmButtonColor: "#ce0808",
        });
  }
}

function alertEliminarCliente(method, data, callback){

  Swal.fire({
  title: "Confirmar Eliminacion",
  text: "Esta accion no se puede revertir",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#18994a",
  cancelButtonColor: "#d33",
  confirmButtonText: "Confirmar",
  cancelButtonText :"Cancelar",

}).then((result) => {
  if (result.isConfirmed) {
      
      fetch(window.location,{method: method, body: data })
      .then(resultado => resultado.json())
      .then(eliminar => {

        if(eliminar.status === "success"){
          Swal.fire({
            title: "Eliminado",
            text: "Cliente Eliminado exitosamente",
            icon: "success",
            confirmButtonColor: "#18994a",
          }).then(() => {
             if(callback){
              callback();
             }

          }
          );

        }
        else if(eliminar.status === "error"){
            Swal.fire({
            title: "Error",
            text: "Error al eliminar el registro",
            icon: "error",
            confirmButtonColor: "#ce0808",
          });

        }
     
      })
  }
});

}