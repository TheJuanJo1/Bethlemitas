document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');

    let group_areas = document.getElementById('group_areas');
    let group_load_group = document.getElementById('group_load_group');
    let group_group_director = document.getElementById('group_group_director');
    let group_load_degree = document.getElementById('group_load_degree');
    let load_degree = document.getElementById('load_degree');
    let areas = document.getElementById('areas');
    let groups = document.getElementById('groups');
    let group_director = document.getElementById('group_director');
    const selectedRoleText = roleSelect.options[roleSelect.selectedIndex].text; // Obtiene el texto del rol seleccionado
    function getSelectedRole() {


        if (selectedRoleText == 'docente') {
            group_areas.style.display = 'block'; // Mostrar el div
            group_load_group.style.display = 'block'; // Mostrar el div
            group_group_director.style.display = 'block'; // Mostrar el div
            group_load_degree.classList.add('hidden');
            load_degree.disabled = true; // Deshabilita el select de grados
            areas.disabled = false;
            groups.disabled = false;
            group_director.disabled = false;
        }

        if (selectedRoleText == 'psicoorientador') {
            group_areas.style.display = 'none'; // Oculta el div
            group_load_group.style.display = 'none'; // Oculta el div
            group_group_director.style.display = 'none'; // Oculta el div
            group_load_degree.classList.remove('hidden');
            load_degree.disabled = false; // Habilita el select de grados
            areas.disabled = true;
            groups.disabled = true;
            group_director.disabled = true;
        }
    }



    // Funcion para mostrar los grupos en donde se imparten cada area (Editar)

    const container = document.getElementById('selected-areas-container');
    const description = document.getElementById('description');
    const areasSelect = document.getElementById('areas'); // Obtener el <select> de areas
    if (selectedRoleText == 'docente') {
        description.innerHTML = 'Para cada area, es necesario asignar el grupo o los grupos en los que el docente llevará a cabo la instrucción correspondiente.';
    }
    function getSelectedAreas() {
        // Limpiar el contenedor antes de agregar nuevos elementos
        container.innerHTML = '';

        // Obtener las áreas seleccionadas
        const selectedAreaIds = Array.from(areasSelect.selectedOptions).map(option => ({
            id: parseInt(option.value),
            name: option.text
        }));

        selectedAreaIds.forEach(area => {
            // Crear div para el área
            const areaDiv = document.createElement('div');
            areaDiv.classList.add('area-item');

            // Input oculto para guardar el ID del área.
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'area_id[]';
            hiddenInput.value = area.id;

            // Campo con el nombre del área (solo lectura)
            const nameSpan = document.createElement('input');
            nameSpan.classList.add('title-area');
            nameSpan.placeholder = area.name;
            nameSpan.value = area.name;
            nameSpan.disabled = true;

            // Crear un <select> para los grupos
            const dbSelect = document.createElement('select');
            dbSelect.classList.add('db-options');
            dbSelect.name = `groups_asig[${area.id}][]`;
            dbSelect.multiple = true;

            // Llenar el <select> con los grupos
            dbOptionsGroups.forEach(group => {
                const optionElement = document.createElement('option');
                optionElement.value = group.id;
                optionElement.text = group.group;

                // Marcar como seleccionados los grupos ya asociados a esta área
                groupsForArea.forEach(groupsArea => {
                    if (groupsArea.area_id === area.id) {
                        groupsArea.groups.forEach(groupAssigned => {
                            if (groupAssigned.id === group.id) {
                                optionElement.selected = true;
                            }
                        });
                    }
                });

                dbSelect.appendChild(optionElement);
            });

            // Añadir elementos al div
            areaDiv.appendChild(hiddenInput);
            areaDiv.appendChild(nameSpan);
            areaDiv.appendChild(dbSelect);
            container.appendChild(areaDiv);
        });
    }
    // Evento para actualizar campos cuando cambia la selección de areas
    areasSelect.addEventListener('change', getSelectedAreas);

    // Llamar a la función inicialmente para cargar las areas seleccionadas
    getSelectedRole();
    getSelectedAreas();

});