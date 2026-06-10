
let BotonesEliminar = document.querySelectorAll(".btn-eliminar");
let BotonesAgregar = document.querySelectorAll(".btn-agregar");

BotonesEliminar.forEach(function(EliminarBtn){

EliminarBtn.addEventListener("click", function(event){
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
  if (result.isConfirmed) Swal.fire({
    title: "Eliminado",
    text: "El Registro ha Sido Eliminado",
    icon: "success",
    confirmButtonColor: "#18994a",
  });
});
});

});

BotonesAgregar.forEach(function(AgregarBtn){

    AgregarBtn.addEventListener("click",function(event){
        Swal.fire({
  title: "Agregado",
  text: "Se ha Agregado Correctamente ",
  icon: "success",
  draggable: true,
  confirmButtonColor: "#18994a",

});
    })
})


