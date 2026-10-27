(() => {
    'use strict';

    const mostrarAlerta = (opciones) => {
        if (window.Swal) {
            return window.Swal.fire(opciones);
        }
        console.error('SweetAlert no está disponible.', opciones);
        return Promise.resolve({ isConfirmed: false });
    };

    const escaparHTML = (valor) => String(valor ?? '').replace(/[&<>"']/g, (caracter) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caracter]);

    const errorServidor = document.querySelector('[data-consulta-error]')?.dataset.consultaError;
    if (errorServidor) {
        mostrarAlerta({ icon: 'error', title: 'Error', text: errorServidor });
    }

    document.querySelectorAll('[data-agregar]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const tipo = boton.dataset.agregar;
            const plantilla = document.getElementById(`${tipo}-template`);
            const nombreContenedor = tipo === 'diagnostico' ? 'diagnosticos-container' : 'recetas-container';
            const destino = document.getElementById(nombreContenedor);

            if (plantilla && destino) {
                destino.appendChild(plantilla.content.cloneNode(true));
            }
        });
    });

    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('.quitar-fila');
        if (boton) {
            boton.closest('.diagnostico-row, .receta-row')?.remove();
        }
    });

    const tablaConsultas = document.getElementById('tabla-consultas');
    if (tablaConsultas) {
        const columnaVacia = '<tr><td colspan="12" class="text-center text-muted py-4">Todavía no hay consultas registradas.</td></tr>';

        const renderizarConsulta = (consulta) => {
            const id = Number(consulta.id_consulta);
            const modalId = `consulta-detalle-${id}`;
            const tipoIngreso = Number(consulta.tipo_ingreso) === 1 ? 'Primera vez' : 'Sucesivo';
            const remitido = Number(consulta.remitido) === 1 ? 'Sí' : 'No';
            return `<tr>
                <td>${id}</td>
                <td>${escaparHTML(consulta.nombre_mascota)}</td>
                <td>${escaparHTML(consulta.nombre_cliente)}</td>
                <td>${escaparHTML(consulta.fecha)}</td>
                <td>${tipoIngreso}</td>
                <td>${remitido}</td>
                <td>${escaparHTML(consulta.pronostico || '—')}</td>
                <td>${escaparHTML(consulta.tratamiento_consulta || '—')}</td>
                <td>${escaparHTML(consulta.peso_consulta || '—')}</td>
                <td>${escaparHTML(consulta.comentario || '—')}</td>
                <td><button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#${modalId}">Ver detalle</button></td>
                <td class="text-nowrap">
                    <a href="index.php?url=NuevaConsulta&amp;id_consulta=${id}" class="btn btn-sm btn-success">Actualizar</a>
                    <form method="post" action="${tablaConsultas.dataset.url}" class="d-inline" data-eliminar-consulta>
                        <input type="hidden" name="id_consulta" value="${id}">
                        <input type="hidden" name="eliminar_consulta" value="1">
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>`;
        };

        const cargarConsultas = async () => {
            const datos = new FormData();
            datos.append('obtener', '1');

            const respuesta = await fetch(tablaConsultas.dataset.url, {
                method: 'POST',
                body: datos,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const resultado = await respuesta.json();
            if (!respuesta.ok || resultado.status !== 'success' || !Array.isArray(resultado.resultados)) {
                throw new Error(resultado.mensaje || 'No fue posible cargar las consultas.');
            }

            tablaConsultas.innerHTML = resultado.resultados.length
                ? resultado.resultados.map(renderizarConsulta).join('')
                : columnaVacia;
        };

        document.addEventListener('submit', async (evento) => {
            const formulario = evento.target.closest('[data-eliminar-consulta]');
            if (!formulario) {
                return;
            }
            evento.preventDefault();
            const confirmacion = await mostrarAlerta({
                title: '¿Eliminar esta consulta?',
                text: 'La consulta dejará de aparecer en el historial. Esta acción no borra sus datos clínicos.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545'
            });
            if (!confirmacion.isConfirmed) {
                return;
            }

            const boton = formulario.querySelector('[type="submit"]');
            boton.disabled = true;
            try {
                const respuesta = await fetch(formulario.action, {
                    method: 'POST',
                    body: new FormData(formulario),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const resultado = await respuesta.json();
                if (!respuesta.ok || resultado.status !== 'success') {
                    throw new Error(resultado.mensaje || 'No se pudo eliminar la consulta.');
                }

                document.getElementById(`consulta-detalle-${formulario.elements.id_consulta.value}`)?.remove();
                await mostrarAlerta({
                    icon: 'success',
                    title: 'Eliminada',
                    text: resultado.mensaje,
                    confirmButtonColor: '#198754'
                });
                await cargarConsultas();
            } catch (error) {
                mostrarAlerta({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo eliminar la consulta.'
                });
            } finally {
                boton.disabled = false;
            }
        });

        cargarConsultas().catch((error) => {
            tablaConsultas.innerHTML = '<tr><td colspan="12" class="text-center text-danger py-4">No fue posible cargar las consultas.</td></tr>';
            mostrarAlerta({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No fue posible cargar las consultas.'
            });
        });
    }

    const formularioConsulta = document.querySelector('[data-consulta-form]');
    if (formularioConsulta) {
        formularioConsulta.addEventListener('submit', async (evento) => {
            evento.preventDefault();
            const boton = formularioConsulta.querySelector('[type="submit"]');
            boton.disabled = true;

            try {
                const respuesta = await fetch(formularioConsulta.action, {
                    method: 'POST',
                    body: new FormData(formularioConsulta),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const resultado = await respuesta.json();
                if (!respuesta.ok || resultado.status !== 'success') {
                    throw new Error(resultado.mensaje || 'No se pudo procesar la consulta.');
                }

                await mostrarAlerta({
                    icon: 'success',
                    title: 'Listo',
                    text: resultado.mensaje,
                    confirmButtonColor: '#198754'
                });
                window.location.href = resultado.redirect;
            } catch (error) {
                mostrarAlerta({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo procesar la consulta.'
                });
            } finally {
                boton.disabled = false;
            }
        });
    }

})();