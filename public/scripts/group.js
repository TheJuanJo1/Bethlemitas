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
            const asignatures = JSON.parse(this.dataset.asignatures); // Asignaturas ya asignadas al grupo

            document.getElementById('groupId').value = groupId;
            document.getElementById('grupo_edit').value = groupName;

            // Selecciona las asignaturas ya asignadas
            const select = document.getElementById('asignaturas_edit');
            Array.from(select.options).forEach(option => {
                // Verifica si la asignatura está asignada al grupo y selecciónala
                if (asignatures.includes(parseInt(option.value))) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            });

            modal.classList.remove('hidden');
        })
    })

    exitButtons.forEach(function(exit) {
        exit.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
})