document.addEventListener('DOMContentLoaded', function() {
    //Obtener el boton de la edicion.
    const editButton = document.querySelectorAll('.edit_degree');
    const modal = document.getElementById('modal_edit_degree');
    const exitButtons = document.querySelectorAll('.button_exit');

    editButton.forEach(button => {
        button.addEventListener('click', function() {
            // Obtén los datos del botón
            const degreeId = this.dataset.id;
            const degreeName = this.getAttribute('name-degree');

            document.getElementById('degreeId').value = degreeId;
            document.getElementById('degree_edit').value = degreeName;

            modal.classList.remove('hidden');
        })
    })

    exitButtons.forEach(function(exit) {
        exit.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
})