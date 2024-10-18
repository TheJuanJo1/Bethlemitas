document.getElementById('role').addEventListener('change', function() {
    let selectedRole = this.options[this.selectedIndex].text.trim(); //obtiene el texto del rol seleccionado

    let group_asignatures = document.getElementById('group_asignatures');
    let group_load_group = document.getElementById('group_load_group');
    let group_group_director = document.getElementById('group_group_director');
    let group_load_degree =  document.getElementById('group_load_degree');
    let load_degree = document.getElementById('load_degree');
    let subjects = document.getElementById('subjects');
    let groups = document.getElementById('groups');
    let group_director = document.getElementById('group_director');

    //Condicion para ocultar el div dependiendo el rol que escoja
    if (selectedRole == 'psicoorientador') {
        group_asignatures.style.display = 'none'; // Oculta el div
        group_load_group.style.display = 'none'; // Oculta el div
        group_group_director.style.display = 'none'; // Oculta el div
        group_load_degree.classList.remove('hidden');
        load_degree.disabled = false; // Habilita el select de grados
        subjects.disabled = true;
        groups.disabled = true;
        group_director.disabled = true;
        
    }

    if (selectedRole == 'docente') {
        group_asignatures.style.display = 'block'; // Mostrar el div
        group_load_group.style.display = 'block'; // Mostrar el div
        group_group_director.style.display = 'block'; // Mostrar el div
        group_load_degree.classList.add('hidden');
        load_degree.disabled = true; // Deshabilita el select de grados
        subjects.disabled = false;
        groups.disabled = false;
        group_director.disabled = false;

    } 
});