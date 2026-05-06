document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const container = document.getElementById('selected-areas-container');
    const description = document.getElementById('description');
    const areasSelect = document.getElementById('areas');

    function updateVisibility() {
        const selectedRole = roleSelect.options[roleSelect.selectedIndex].text.trim().toLowerCase();
        
        let group_areas = document.getElementById('group_areas');
        let group_load_group = document.getElementById('group_load_group');
        let group_group_director = document.getElementById('group_group_director');
        let group_load_degree = document.getElementById('group_load_degree');
        
        let load_degree = document.getElementById('load_degree');
        let areas = document.getElementById('areas');
        let groups = document.getElementById('groups');
        let group_director = document.getElementById('group_director');

        if (selectedRole === 'docente') {
            group_areas.style.display = 'block';
            group_load_group.style.display = 'block';
            group_group_director.style.display = 'block';
            group_load_degree.style.display = 'none';
            
            load_degree.disabled = true;
            areas.disabled = false;
            groups.disabled = false;
            group_director.disabled = false;
            
            description.innerHTML = 'Para cada area, es necesario asignar el grupo o los grupos en los que el docente llevará a cabo la instrucción correspondiente.';
            renderAreaAssignments();
        } else if (selectedRole === 'psicoorientador') {
            group_areas.style.display = 'none';
            group_load_group.style.display = 'none';
            group_group_director.style.display = 'none';
            group_load_degree.style.display = 'block';
            
            load_degree.disabled = false;
            areas.disabled = true;
            groups.disabled = true;
            group_director.disabled = true;
            
            description.innerHTML = '';
            container.innerHTML = '';
        } else {
            // Caso por defecto (Coordinador u otros)
            group_areas.style.display = 'none';
            group_load_group.style.display = 'none';
            group_group_director.style.display = 'none';
            group_load_degree.style.display = 'none';
            description.innerHTML = '';
            container.innerHTML = '';
        }
    }

    function renderAreaAssignments() {
        container.innerHTML = '';
        const selectedAreas = Array.from(areasSelect.selectedOptions).map(option => ({
            id: parseInt(option.value),
            name: option.text
        }));

        selectedAreas.forEach(area => {
            if (!area.id) return;

            const areaDiv = document.createElement('div');
            areaDiv.classList.add('area-item');

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'area_id[]';
            hiddenInput.value = area.id;

            const nameSpan = document.createElement('input');
            nameSpan.classList.add('title-area');
            nameSpan.value = area.name;
            nameSpan.disabled = true;

            const dbSelect = document.createElement('select');
            dbSelect.classList.add('db-options');
            dbSelect.name = `groups_asig[${area.id}][]`;
            dbSelect.multiple = true;

            dbOptionsGroups.forEach(group => {
                const optionElement = document.createElement('option');
                optionElement.value = group.id;
                optionElement.text = group.group;

                // Pre-seleccionar grupos si ya existen en groupsForArea
                if (typeof groupsForArea !== 'undefined') {
                    const existingAssignment = groupsForArea.find(g => g.area_id == area.id);
                    if (existingAssignment) {
                        if (existingAssignment.groups.some(ga => ga.id == group.id)) {
                            optionElement.selected = true;
                        }
                    }
                }

                dbSelect.appendChild(optionElement);
            });

            areaDiv.appendChild(hiddenInput);
            areaDiv.appendChild(nameSpan);
            areaDiv.appendChild(dbSelect);
            container.appendChild(areaDiv);
        });
    }

    roleSelect.addEventListener('change', updateVisibility);
    areasSelect.addEventListener('change', renderAreaAssignments);

    // Inicialización
    updateVisibility();
});
