document.addEventListener('DOMContentLoaded', function() {
    //Obtener el boton de la edicion.
    const editButton = document.querySelectorAll('.edit_area');
    const modal = document.getElementById('modal_edit_area');
    const exitButtons = document.querySelectorAll('.button_exit');

    editButton.forEach(button => {
        button.addEventListener('click', function() {
            // Obtén los datos del botón
            const areaId = this.dataset.id;
            const areaName = this.getAttribute('name-area');

            document.getElementById('areaId').value = areaId;
            document.getElementById('area_edit').value = areaName;

            modal.classList.remove('hidden');
        })
    })

    exitButtons.forEach(function(exit) {
        exit.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
})