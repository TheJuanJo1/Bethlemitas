document.addEventListener('DOMContentLoaded', function () {
    // Obtener elementos del DOM
    const acceptButtons = document.querySelectorAll('.acceptButton'); // Todos los botones "Aceptar"
    const closeButton = document.getElementById('closeButton');       // Botón para cerrar el modal
    const hiddenContainer = document.getElementById('hiddenContainer'); // Contenedor modal
    const contentTitleWaiting = document.getElementById('content_title_waiting'); // Encabezado sticky

    // Función para mostrar el contenedor oculto y quitar 'sticky'
    function showHiddenContainer() {
        hiddenContainer.classList.remove('hidden'); // Mostrar el modal
        contentTitleWaiting.classList.remove('sticky'); // Quitar sticky
        contentTitleWaiting.style.position = 'static'; // Cambiar posición a estática
        // Obtener el id del estudiante que se guarda en el boton
        const studentId = this.dataset.id;

        document.getElementById('studentId').value = studentId;
        
    }

    // Función para ocultar el contenedor y restaurar 'sticky'
    function hideHiddenContainer() {
        hiddenContainer.classList.add('hidden'); // Ocultar el modal
        contentTitleWaiting.classList.add('sticky'); // Restaurar sticky
        contentTitleWaiting.style.position = ''; // Restaurar posición a su valor original
    }

    // Agregar evento a cada botón de aceptar
    acceptButtons.forEach(button => {
        button.addEventListener('click', showHiddenContainer);
    });

    // Evento para cerrar el modal
    closeButton.addEventListener('click', hideHiddenContainer);
});
