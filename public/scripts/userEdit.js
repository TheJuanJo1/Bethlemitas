document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');

    let group_asignatures = document.getElementById('group_asignatures');
    let group_load_group = document.getElementById('group_load_group');
    let group_group_director = document.getElementById('group_group_director');
    let group_load_degree =  document.getElementById('group_load_degree');
    let load_degree = document.getElementById('load_degree');
    let subjects = document.getElementById('subjects');
    let groups = document.getElementById('groups');
    let group_director = document.getElementById('group_director');

    function getSelectedRole() {
        const selectedRoleText = roleSelect.options[roleSelect.selectedIndex].text; // Obtiene el texto del rol seleccionado

        if (selectedRoleText == 'docente') {
            group_asignatures.style.display = 'block'; // Mostrar el div
            group_load_group.style.display = 'block'; // Mostrar el div
            group_group_director.style.display = 'block'; // Mostrar el div
            group_load_degree.classList.add('hidden');
            load_degree.disabled = true; // Deshabilita el select de grados
            subjects.disabled = false;
            groups.disabled = false;
            group_director.disabled = false;
        }

        if (selectedRoleText == 'psicoorientador') {
            group_asignatures.style.display = 'none'; // Oculta el div
            group_load_group.style.display = 'none'; // Oculta el div
            group_group_director.style.display = 'none'; // Oculta el div
            group_load_degree.classList.remove('hidden');
            load_degree.disabled = false; // Habilita el select de grados
            subjects.disabled = true;
            groups.disabled = true;
            group_director.disabled = true;
        }
    }

    // Funcion para mostrar los grupos en donde se imparten cada asignatura (Editar)

    const container = document.getElementById('selected-asignatures-container');
    const description = document.getElementById('description');
    const subjectsSelect = document.getElementById('subjects'); // Obtener el <select> de asignaturas

    description.innerHTML = 'Para cada asignatura, es necesario asignar el grupo o los grupos en los que el docente llevará a cabo la instrucción correspondiente.';

    function getSelectedAsignatures() {
        // Limpiar el contenedor antes de agregar nuevos elementos
        container.innerHTML = '';
        
        // Obtener las asignaturas seleccionadas
        const selectedAsignatureIds = Array.from(subjectsSelect.selectedOptions).map(option => ({
            id: parseInt(option.value),
            name: option.text
        }));

        selectedAsignatureIds.forEach(asignature => {
            // Crear div para la asignatura
            const asignatureDiv = document.createElement('div');
            asignatureDiv.classList.add('asignature-item');

            // Input oculto para guardar el ID de la asignatura.
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'asignature_id[]';
            hiddenInput.value = asignature.id;

            // Crear campo en donde se muestra el nombre de la asignatura
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
            dbOptionsGroups.forEach(group => {
                const optionElement = document.createElement('option');
                optionElement.value = group.id;
                optionElement.text = group.group;

                // Verificar si el grupo pertenece a esta asignatura
                groupsForAsignature.forEach(groupsSubjects => {
                    if (groupsSubjects.asignature_id === asignature.id) {
                        groupsSubjects.groups.forEach(groupAssigned => {
                            if (groupAssigned.id === group.id) {
                                optionElement.selected = true;
                            }
                        });
                    }
                });

                dbSelect.appendChild(optionElement);
            });

            // Añadir los elementos creados al div de asignaturas
            asignatureDiv.appendChild(hiddenInput);
            asignatureDiv.appendChild(nameSpan);
            asignatureDiv.appendChild(dbSelect);
            container.appendChild(asignatureDiv);
        });
    }

    // Evento para actualizar campos cuando cambia la selección de asignaturas
    subjectsSelect.addEventListener('change', getSelectedAsignatures);

    // Llamar a la función inicialmente para cargar las asignaturas seleccionadas
    getSelectedRole();
    getSelectedAsignatures();

});