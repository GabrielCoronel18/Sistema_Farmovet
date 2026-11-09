// =============================================
// ajax.js  -  Peticiones AJAX centralizadas
// Forma 2 del profesor: todas las peticiones
// del sistema pasan por esta función.
// =============================================

/**
 * Envía una petición AJAX POST con FormData.
 *
 * @param {FormData} datos      - Los datos a enviar
 * @param {Function} onSuccess  - Callback si la petición va bien
 * @param {Function} onError    - Callback si hay error (opcional)
 * @param {String}   url        - URL destino (por defecto: window.location)
 */
function peticionAjax(datos, onSuccess, onError, url) {

    $.ajax({
        url: url || window.location,
        type: "POST",
        data: datos,
        processData: false,   // OBLIGATORIO con FormData
        contentType: false,   // OBLIGATORIO con FormData
        dataType: "json",

        success: function (res) {
            if (typeof onSuccess === "function") {
                onSuccess(res);
            }
        },

        error: function (xhr, status, error) {
            if (typeof onError === "function") {
                onError(xhr, status, error);
            } else {
                console.error("Error AJAX:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo comunicar con el servidor.'
                });
            }
        }
    });
}