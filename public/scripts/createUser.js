document.getElementById('role').addEventListener('change', function() {
    let selectedRole = this.options[this.selectedIndex].text.trim(); //obtiene el texto del rol seleccionado

    let group_asignatures = document.getElementById('group_asignatures');
    let group_load_group = document.getElementById('group_load_group');
    let group_group_director = document.getElementById('group_group_director');
    let group_load_degree =  document.getElementById('group_load_degree');

    //Condicion para ocultar el div dependiendo el rol que escoja
    if (selectedRole == 'psicoorientador') {
        group_asignatures.style.display = 'none'; // Oculta el div
        group_load_group.style.display = 'none'; // Oculta el div
        group_group_director.style.display = 'none'; // Oculta el div
        group_load_degree.classList.remove('hidden');
    }

    if (selectedRole == 'docente') {
        group_asignatures.style.display = 'block'; // Oculta el div
        group_load_group.style.display = 'block'; // Oculta el div
        group_group_director.style.display = 'block'; // Oculta el div
        group_load_degree.classList.add('hidden');
    } 
});