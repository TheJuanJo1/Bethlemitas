document.addEventListener('DOMContentLoaded', function () {

    // ─── 1. DECLARAR ELEMENTOS DEL DOM ────────────────────────────────────────
    const roleSelect           = document.getElementById('role');
    const areasSelect          = document.getElementById('areas');
    const groupsSelect         = document.getElementById('groups');
    const directorSelect       = document.getElementById('group_director');
    const degreeSelect         = document.getElementById('load_degree');

    const groupAreasDiv        = document.getElementById('group_areas');
    const groupLoadGroupDiv    = document.getElementById('group_load_group');
    const groupGroupDirectorDiv = document.getElementById('group_group_director');
    const groupLoadDegreeDiv   = document.getElementById('group_load_degree');

    // ─── 2. FUNCIONES ────────────────────────────────────────────────────────
    function resetAll() {
        groupAreasDiv.style.display         = 'none';
        groupLoadGroupDiv.style.display     = 'none';
        groupGroupDirectorDiv.style.display = 'none';
        groupLoadDegreeDiv.style.display    = 'none';

        areasSelect.disabled    = true;
        groupsSelect.disabled   = true;
        directorSelect.disabled = true;
        degreeSelect.disabled   = true;
    }

    function setForDocente() {
        resetAll();
        groupAreasDiv.style.display         = 'block';
        groupLoadGroupDiv.style.display     = 'block';
        groupGroupDirectorDiv.style.display = 'block';
        groupLoadDegreeDiv.style.display    = 'none';

        areasSelect.disabled    = false;
        groupsSelect.disabled   = false;
        directorSelect.disabled = false;
        degreeSelect.disabled   = true;
    }

    function setForPsicoorientador() {
        resetAll();
        groupAreasDiv.style.display         = 'none';
        groupLoadGroupDiv.style.display     = 'none';
        groupGroupDirectorDiv.style.display = 'none';
        groupLoadDegreeDiv.style.display    = 'block';

        areasSelect.disabled    = true;
        groupsSelect.disabled   = true;
        directorSelect.disabled = true;
        degreeSelect.disabled   = false;
    }

    // ─── 3. ESTADO INICIAL ───────────────────────────────────────────────────
    resetAll(); // oculta todo al cargar la página

    // Si el rol ya está pre-seleccionado (ej: al volver con errores de validación)
    if (roleSelect) {
        const initialVal = roleSelect.value.trim().toLowerCase();
        if (initialVal === 'docente') {
            setForDocente();
        } else if (initialVal === 'psicoorientador') {
            setForPsicoorientador();
        }
    }

    // ─── 4. LISTENER: CAMBIO DE ROL ──────────────────────────────────────────
    if (roleSelect) {
        roleSelect.addEventListener('change', function () {
            const val  = this.value.trim().toLowerCase();
            const text = this.options[this.selectedIndex].text.trim().toLowerCase();
            console.log('[createUser] Rol → value:', val, '| texto:', text);

            if (val === 'docente' || text.includes('docente')) {
                setForDocente();
            } else if (val === 'psicoorientador' || text.includes('psicoorientador')) {
                setForPsicoorientador();
            } else {
                resetAll();
            }
        });
    }

    // ─── 5. LISTENER: SELECCIÓN DE ÁREAS (solo para docente) ─────────────────
    if (areasSelect) {
        areasSelect.addEventListener('change', function () {
            const selectedAreas = Array.from(this.selectedOptions).map(opt => ({
                id: opt.value,
                name: opt.text.trim()
            }));

            const container  = document.getElementById('selected-areas-container');
            const description = document.getElementById('description');

            container.innerHTML = '';
            description.textContent = selectedAreas.length
                ? 'Para cada área, asigna el/los grupo(s) donde el docente impartirá la materia.'
                : '';

            selectedAreas.forEach(area => {
                const areaDiv = document.createElement('div');
                areaDiv.classList.add('area-item');
                areaDiv.dataset.id = area.id;

                const hiddenInput = document.createElement('input');
                hiddenInput.type  = 'hidden';
                hiddenInput.name  = 'area_id[]';
                hiddenInput.value = area.id;

                const nameInput = document.createElement('input');
                nameInput.classList.add('title-area');
                nameInput.placeholder = area.name;
                nameInput.value       = area.name;
                nameInput.disabled    = true;

                const dbSelect = document.createElement('select');
                dbSelect.classList.add('db-options');
                dbSelect.name     = `groups_asig[${area.id}][]`;
                dbSelect.multiple = true;

                if (typeof dbOptions !== 'undefined') {
                    dbOptions.forEach(group => {
                        const opt  = document.createElement('option');
                        opt.value  = group.id;
                        opt.text   = group.group;
                        dbSelect.appendChild(opt);
                    });
                }

                areaDiv.appendChild(hiddenInput);
                areaDiv.appendChild(nameInput);
                areaDiv.appendChild(dbSelect);
                container.appendChild(areaDiv);
            });
        });
    }
});