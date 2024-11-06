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

document.getElementById('subjects').addEventListener('change', function() {
    const selectedAsignatures = Array.from(this.selectedOptions).map(option => ({
        id: option.value,
        name: option.text
    }));

    const container = document.getElementById('selected-asignatures-container');
    container.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos elementos
    const description = document.getElementById('description');
    description.innerHTML = '';
    description.innerHTML = 'Para cada asignatura, es necesario asignar el grupo o los grupos en los que el docente llevará a cabo la instrucción correspondiente.';

    selectedAsignatures.forEach(asignature => {
        const asignatureDiv = document.createElement('div');
        asignatureDiv.classList.add('asignature-item');
        asignatureDiv.setAttribute('data-id', asignature.id);

        // Crear input oculto para evaluar el id de la asignatura.
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'asignature_id[]';
        hiddenInput.value = asignature.id;

        // Mostrar el nombre de la asignatura
        const nameSpan = document.createElement('input');
        nameSpan.classList.add('title-asignature');
        nameSpan.placeholder = asignature.name;
        nameSpan.value = asignature.name;
        nameSpan.disabled = true; 

        // Crear un <select> adicional para opciones de la base de datos
        const dbSelect = document.createElement('select');
        dbSelect.classList.add('db-options');
        dbSelect.name = `groups_asig[${asignature.id}][]`; // Agregar [] al name
        dbSelect.multiple = true; // Establecer el select como múltiple

        // Llenar el <select> con las opciones de la base de datos
        dbOptions.forEach(group => {
            const optionElement = document.createElement('option');
            optionElement.value = group.id;
            optionElement.text = group.group;
            dbSelect.appendChild(optionElement);
        });

        // Agregar los elementos al contenedor de la asignatura
        asignatureDiv.appendChild(hiddenInput);
        asignatureDiv.appendChild(nameSpan);
        asignatureDiv.appendChild(dbSelect);
        container.appendChild(asignatureDiv);
    });
});