(() => {
    'use strict';

    const alertar = (opciones) => {
        if (window.Swal) {
            return window.Swal.fire(opciones);
        }
        console.error('SweetAlert2 no está disponible.', opciones);
        return Promise.resolve({ isConfirmed: false });
    };

    const confirmar = async () => {
        if (!window.Swal) {
            console.error('SweetAlert2 no está disponible para confirmar la eliminación.');
            return false;
        }
        const resultado = await window.Swal.fire({
            icon: 'warning',
            title: '¿Eliminar consulta?',
            text: 'La consulta dejará de aparecer en el historial.',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        });
        return resultado.isConfirmed;
    };

    const formulario = document.querySelector('[data-consulta-form]');
    if (formulario) {
        const urlFormulario = formulario.action;
        const idEdicion = Number(new URLSearchParams(window.location.search).get('id_consulta') || 0);
        let catalogos = {
            patologias: [],
            medicamentos: []
        };

        const activarTomSelect = (select) => {
            if (!window.TomSelect || select.tomselect) return;
            new TomSelect(select, {
                create: false,
                allowEmptyOption: true,
                placeholder: select.dataset.placeholder || 'Seleccione una opción'
            });
        };

        const llenarSelect = (selector, opciones, valor) => {
            document.querySelectorAll(selector).forEach((select) => {
                if (select.tomselect) select.tomselect.destroy();
                const textoInicial = select.options[0]?.textContent || 'Seleccione';
                select.innerHTML = `<option value="">${textoInicial}</option>`;
                opciones.forEach((opcion) => {
                    const option = document.createElement('option');
                    option.value = opcion.id;
                    option.textContent = opcion.nombre;
                    option.selected = String(opcion.id) === String(valor || '');
                    select.appendChild(option);
                });
                activarTomSelect(select);
            });
        };

        const establecerValores = (datos) => {
            Object.entries(datos || {}).forEach(([campo, valor]) => {
                const elemento = formulario.querySelector(`[name="${campo}"]`);
                if (elemento && !Array.isArray(valor)) {
                    if (elemento.tomselect) {
                        elemento.tomselect.setValue(String(valor ?? ''));
                    } else {
                        elemento.value = valor ?? '';
                    }
                }
            });
        };

        const cargarDatosFormulario = async () => {
            const catalogosRespuesta = await fetch(urlFormulario, {
                method: 'POST',
                body: new URLSearchParams({ obtenerCatalogos: '1' }),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const respuestaCatalogos = await catalogosRespuesta.json();
            if (respuestaCatalogos.status !== 'success') throw new Error('No fue posible cargar los catálogos.');
            llenarSelect('#buscarPaciente', respuestaCatalogos.mascotas.map((item) => ({
                id: item.id_mascota,
                nombre: `${item.nombre} - ${item.nombre_cliente}`
            })));
            catalogos.patologias = respuestaCatalogos.patologias.map((item) => ({
                id: item.id_patologia,
                nombre: item.nombre
            }));
            catalogos.medicamentos = respuestaCatalogos.medicamentos.map((item) => ({
                id: item.id_medicamento,
                nombre: item.nombre_medicamento
            }));
            llenarSelect('[name="id_patologia[]"]', catalogos.patologias);
            llenarSelect('[name="id_medicamento[]"]', catalogos.medicamentos);

            if (!idEdicion) return;
            const consultaRespuesta = await fetch(urlFormulario, {
                method: 'POST',
                body: new URLSearchParams({ obtenerConsulta: '1', id_consulta: idEdicion }),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const consultaResultado = await consultaRespuesta.json();
            if (consultaResultado.status !== 'success') throw new Error('No fue posible cargar la consulta.');
            const consulta = consultaResultado.resultado;
            establecerValores(consulta);
            establecerValores(consulta.anamnesis);
            establecerValores(consulta.examen);
            establecerValores(consulta.laboratorio);
            formulario.querySelector('[name="id_consulta"]').value = idEdicion;

            const diagnosticoInicial = document.querySelector('.diagnostico-row');
            const recetaInicial = document.querySelector('.receta-row');
            (consulta.diagnosticos || []).forEach((item, indice) => {
                let fila = indice === 0 ? diagnosticoInicial : null;
                if (!fila) {
                    document.querySelector('[data-agregar="diagnostico"]').click();
                    fila = document.querySelectorAll('.diagnostico-row')[indice];
                }
                fila.querySelector('[name="id_diagnostico[]"]').value = item.id_diagnostico;
                fila.querySelector('[name="id_patologia[]"]').tomselect?.setValue(String(item.id_patologia));
                fila.querySelector('[name="tipo_diagnostico[]"]').value = item.tipo_diagnostico;
            });
            (consulta.recetas || []).forEach((item, indice) => {
                let fila = indice === 0 ? recetaInicial : null;
                if (!fila) {
                    document.querySelector('[data-agregar="receta"]').click();
                    fila = document.querySelectorAll('.receta-row')[indice];
                }
                fila.querySelector('[name="id_recipe[]"]').value = item.id_recipe;
                fila.querySelector('[name="id_medicamento[]"]').tomselect?.setValue(String(item.id_medicamento));
                fila.querySelector('[name="dosis[]"]').value = item.dosis;
                fila.querySelector('[name="frecuencia[]"]').value = item.frecuencia;
                fila.querySelector('[name="duracion[]"]').value = item.duracion;
            });
        };

        const fecha = formulario.querySelector('[name="fecha"]');
        if (fecha && !fecha.value) {
            fecha.value = new Date().toISOString().slice(0, 10);
        }
        cargarDatosFormulario().catch((error) => alertar({ icon: 'error', title: 'Error', text: error.message }));

        formulario.addEventListener('submit', async (evento) => {
            evento.preventDefault();
            const boton = formulario.querySelector('button[type="submit"]');
            if (boton) boton.disabled = true;
            try {
                const respuesta = await fetch(formulario.action, {
                    method: 'POST',
                    body: new FormData(formulario),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const resultado = await respuesta.json();
                if (!respuesta.ok || resultado.status !== 'success') {
                    throw new Error(resultado.mensaje || 'No fue posible guardar la consulta.');
                }
                await alertar({ icon: 'success', title: 'Consulta guardada', text: resultado.mensaje || 'La consulta fue guardada.' });
                window.location.href = resultado.redirect || 'index.php?url=Consulta';
            } catch (error) {
                await alertar({ icon: 'error', title: 'Error', text: error.message });
                if (boton) boton.disabled = false;
            }
        });
    }

    document.querySelectorAll('[data-agregar]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const tipo = boton.dataset.agregar;
            const plantilla = document.getElementById(`${tipo}-template`);
            const destino = document.getElementById(tipo === 'diagnostico'
                ? 'diagnosticos-container'
                : 'recetas-container');
            if (plantilla && destino) {
                destino.appendChild(plantilla.content.cloneNode(true));
                const fila = destino.lastElementChild;
                const selector = tipo === 'diagnostico' ? '[name="id_patologia[]"]' : '[name="id_medicamento[]"]';
                const nuevo = fila.querySelector(selector);
                const opciones = tipo === 'diagnostico' ? catalogos.patologias : catalogos.medicamentos;
                if (nuevo) {
                    nuevo.innerHTML = '<option value="">Seleccione una opción</option>';
                    opciones.forEach((opcion) => {
                        nuevo.add(new Option(opcion.nombre, opcion.id));
                    });
                    activarTomSelect(nuevo);
                }
            }
        });
    });

    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('.quitar-fila');
        if (boton) boton.closest('.diagnostico-row, .receta-row')?.remove();
    });

    const tabla = document.getElementById('tabla-consultas');
    if (!tabla) return;

    const escapar = (valor) => String(valor ?? '').replace(/[&<>"']/g, (caracter) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    })[caracter]);

    const detalle = (consulta) => {
        const id = Number(consulta.id_consulta);
        const modalId = `consulta-detalle-${id}`;
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = modalId;
        modal.tabIndex = -1;
        modal.innerHTML = `<div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
            <div class="modal-header"><div><h5 class="modal-title">Consulta #${id}</h5>
            <div class="small text-white-50">${escapar(consulta.nombre_mascota)} · ${escapar(consulta.fecha)}</div></div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
            <div class="modal-body"><dl class="row mb-0">
            <div class="col-md-6 mb-3"><dt>Paciente / responsable</dt><dd>${escapar(consulta.nombre_mascota)} / ${escapar(consulta.nombre_cliente)}</dd></div>
            <div class="col-md-6 mb-3"><dt>Fecha y tipo</dt><dd>${escapar(consulta.fecha)} · ${Number(consulta.tipo_ingreso) === 1 ? 'Primera vez' : 'Sucesivo'}</dd></div>
            <div class="col-md-6 mb-3"><dt>Remitido</dt><dd>${Number(consulta.remitido) === 1 ? 'Sí' : 'No'}</dd></div>
            <div class="col-md-6 mb-3"><dt>Pronóstico</dt><dd>${escapar(consulta.pronostico || '—')}</dd></div>
            <div class="col-md-6 mb-3"><dt>Tratamiento</dt><dd>${escapar(consulta.tratamiento_consulta || '—')}</dd></div>
            <div class="col-md-6 mb-3"><dt>Peso</dt><dd>${escapar(consulta.peso_consulta || '—')}</dd></div>
            <div class="col-12 mb-3"><dt>Seguimiento</dt><dd>${escapar(consulta.comentario || '—')}</dd></div>
            </dl>${renderizarSeccion('Anamnesis', consulta.detalle?.anamnesis)}
            ${renderizarSeccion('Examen clínico', consulta.detalle?.examen)}
            ${renderizarSeccion('Laboratorio', consulta.detalle?.laboratorio)}
            ${renderizarLista('Diagnósticos', consulta.detalle?.diagnosticos, 'patologia', 'tipo_diagnostico')}
            ${renderizarLista('Recipe', consulta.detalle?.recetas, 'nombre_medicamento', 'dosis')}
            </div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
        </div></div>`;
        document.body.appendChild(modal);
        return modalId;
    };

    const renderizarSeccion = (titulo, datos) => {
        if (!datos) return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6><p class="text-muted">Sin información registrada.</p>`;
        const contenido = Object.entries(datos).filter(([campo]) => !campo.startsWith('id_') && datos[campo] !== null && datos[campo] !== '')
            .map(([campo, valor]) => `<div class="col-md-6 mb-2"><dt>${escapar(campo.replaceAll('_', ' '))}</dt><dd>${escapar(valor)}</dd></div>`).join('');
        return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6><dl class="row">${contenido || '<p class="text-muted">Sin información registrada.</p>'}</dl>`;
    };

    const renderizarLista = (titulo, datos, campoPrincipal, campoSecundario) => {
        const contenido = (datos || []).map((item) => `<li class="list-group-item"><strong>${escapar(item[campoPrincipal])}</strong> — ${escapar(item[campoSecundario])}</li>`).join('');
        return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6>${contenido ? `<ul class="list-group">${contenido}</ul>` : '<p class="text-muted">Sin información registrada.</p>'}`;
    };

    const cargarConsultas = async () => {
        const url = tabla.dataset.url || 'index.php?url=Consulta';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: new URLSearchParams({ obtener: '1' }),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const resultado = await respuesta.json();
        if (!respuesta.ok || resultado.status !== 'success') throw new Error(resultado.mensaje || 'No fue posible cargar las consultas.');
        tabla.replaceChildren();
        (resultado.resultados || []).forEach((consulta) => {
            const id = Number(consulta.id_consulta);
            const modalId = detalle(consulta);
            const fila = document.createElement('tr');
            fila.innerHTML = `<td>${id}</td><td>${escapar(consulta.nombre_mascota)}</td>
                <td>${escapar(consulta.nombre_cliente)}</td><td>${escapar(consulta.fecha)}</td>
                <td>${Number(consulta.tipo_ingreso) === 1 ? 'Primera vez' : 'Sucesivo'}</td>
                <td>${Number(consulta.remitido) === 1 ? 'Sí' : 'No'}</td>
                <td>${escapar(consulta.pronostico || '—')}</td><td>${escapar(consulta.tratamiento_consulta || '—')}</td>
                <td>${escapar(consulta.peso_consulta || '—')}</td><td>${escapar(consulta.comentario || '—')}</td>
                <td><button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#${modalId}">Ver detalle</button></td>
                <td class="text-nowrap"><a href="index.php?url=NuevaConsulta&id_consulta=${id}" class="btn btn-sm btn-success">Actualizar</a>
                <button type="button" class="btn btn-sm btn-danger" data-eliminar-consulta="${id}">Eliminar</button></td>`;
            tabla.appendChild(fila);
        });
        if (!tabla.children.length) tabla.innerHTML = '<tr><td colspan="12" class="text-center text-muted py-4">Todavía no hay consultas registradas.</td></tr>';
    };

    tabla.addEventListener('click', async (evento) => {
        const boton = evento.target.closest('[data-eliminar-consulta]');
        if (!boton || !(await confirmar())) return;
        boton.disabled = true;
        try {
            const respuesta = await fetch(tabla.dataset.url || 'index.php?url=Consulta', {
                method: 'POST',
                body: new URLSearchParams({ eliminar: '1', id: boton.dataset.eliminarConsulta }),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const resultado = await respuesta.json();
            await alertar({
                icon: resultado.status === 'success' ? 'success' : 'error',
                title: resultado.status === 'success' ? 'Consulta eliminada' : 'Error',
                text: resultado.status === 'success'
                    ? 'La consulta fue eliminada correctamente.'
                    : 'No fue posible eliminar la consulta.'
            });
            if (resultado.status === 'success') {
                cargarConsultas();
            } else {
                boton.disabled = false;
            }
        } catch (error) {
            boton.disabled = false;
            await alertar({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No fue posible eliminar la consulta.'
            });
        }
    });

    cargarConsultas().catch((error) => alertar({ icon: 'error', title: 'Error', text: error.message }));
})();
