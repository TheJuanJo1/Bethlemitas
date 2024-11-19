document.addEventListener('DOMContentLoaded', function() {
    //Obtener el boton de la edicion.
    const editButton = document.querySelectorAll('.edit_asignature');
    const modal = document.getElementById('modal_edit_asignature');
    const exitButtons = document.querySelectorAll('.button_exit');

    editButton.forEach(button => {
        button.addEventListener('click', function() {
            // Obtén los datos del botón
            const asignatureId = this.dataset.id;
            const asignatureName = this.getAttribute('name-asignature');

            document.getElementById('asignatureId').value = asignatureId;
            document.getElementById('asignature_edit').value = asignatureName;

            modal.classList.remove('hidden');
        })
    })

    exitButtons.forEach(function(exit) {
        exit.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
})