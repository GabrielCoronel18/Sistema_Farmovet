const urlParams = new URLSearchParams(window.location.search);
    
const AccesoDenegado = urlParams.get('error');

if (AccesoDenegado === 'acceso_denegado') {
    alertAccesoDenegado();
}