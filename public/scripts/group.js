document.addEventListener('DOMContentLoaded', function() {
    //Obtener el boton de la edicion.
    const editButton = document.querySelectorAll('.edit_group');
    const modal = document.getElementById('modal_edit_group');
    const exitButtons = document.querySelectorAll('.button_exit');

   editButton.forEach(button => {
        button.addEventListener('click', function() {
            // Obtén los datos del botón
            const groupId = this.dataset.id;
            const groupName = this.getAttribute('name-group');

            document.getElementById('groupId').value = groupId;
            document.getElementById('grupo_edit').value = groupName;

            // Selecciona las asignaturas ya asignadas
         
            modal.classList.remove('hidden');
        })
    })

    exitButtons.forEach(function(exit) {
        exit.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
})