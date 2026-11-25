function inicializarMenuMovil() {
	const menu = document.querySelector(".sidebar");
	const botonMenu = document.querySelector("[data-sidebar-toggle]");
	const fondo = document.querySelector("[data-sidebar-close]");

	if (!menu || !botonMenu || !fondo) return;

	function cambiarEstadoMenu(abierto) {
		menu.classList.toggle("is-open", abierto);
		document.body.classList.toggle("sidebar-open", abierto);
		botonMenu.setAttribute("aria-expanded", String(abierto));
		botonMenu.setAttribute("aria-label", abierto ? "Cerrar menú de navegación" : "Abrir menú de navegación");
	}

	botonMenu.addEventListener("click", () => {
		cambiarEstadoMenu(!menu.classList.contains("is-open"));
	});

	fondo.addEventListener("click", () => cambiarEstadoMenu(false));

	document.addEventListener("keydown", event => {
		if (event.key === "Escape" && menu.classList.contains("is-open")) {
			cambiarEstadoMenu(false);
			botonMenu.focus();
		}
	});

	window.matchMedia("(min-width: 992px)").addEventListener("change", event => {
		if (event.matches) cambiarEstadoMenu(false);
	});
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", inicializarMenuMovil, { once: true });
} else {
	inicializarMenuMovil();
}
