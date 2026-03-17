<?php

namespace App\Http\Requests\Academic;

use App\Models\Teachers_areas_group;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('role')) {
            $role = Role::find($this->input('role'));
            if ($role) {
                $this->merge(['role_name' => $role->name]);
            }
        }
    }

    public function rules(): array
    {
        $roleId = $this->input('role');
        $role = $roleId ? Role::find($roleId) : null;
        $roleName = $role?->name;

        $existing = null;
        if ($this->filled('number_documment')) {
            $existing = Users_teacher::where('number_documment', $this->input('number_documment'))->first();
        }
        $ignoreId = $existing?->id;

        $base = [
            'role' => ['required', 'exists:roles,id'],
            'role_name' => ['nullable', 'string'],
        ];

        if ($roleName === 'docente') {
            return $base + [
                'number_documment' => [
                    'required',
                    'digits_between:1,11',
                    Rule::unique('users_teachers', 'number_documment')->ignore($ignoreId),
                ],
                'name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users_teachers', 'email')->ignore($ignoreId),
                ],
                'group_director' => [
                    'nullable',
                    Rule::unique('users_teachers', 'group_director')->ignore($ignoreId),
                ],
                'areas' => ['required', 'array', 'min:1'],
                'areas.*' => ['integer', 'exists:areas,id'],
                'groups' => ['required', 'array', 'min:1'],
                'groups.*' => ['integer', 'exists:groups,id'],
                'area_id' => ['required', 'array', 'min:1'],
                'area_id.*' => ['integer', 'exists:areas,id'],
                'groups_asig' => ['required', 'array'],
                'groups_asig.*' => ['array'],
                'groups_asig.*.*' => ['integer', 'exists:groups,id'],
            ];
        }

        if ($roleName === 'psicoorientador') {
            return $base + [
                'number_documment' => [
                    'required',
                    'digits_between:1,11',
                    Rule::unique('users_teachers', 'number_documment')->ignore($ignoreId),
                ],
                'name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users_teachers', 'email')->ignore($ignoreId),
                ],
                'load_degree' => ['required', 'array', 'min:1'],
                'load_degree.*' => ['integer', 'exists:degrees,id'],
            ];
        }

        return $base;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $roleId = $this->input('role');
            $role = $roleId ? Role::find($roleId) : null;
            $roleName = $role?->name;

            if ($roleName === 'psicoorientador') {
                $existing = Users_teacher::where('number_documment', $this->input('number_documment'))->first();
                $ignoreUserId = $existing?->id;

                foreach ((array) $this->input('load_degree', []) as $degreeId) {
                    $taken = Users_load_degree::where('id_degree', $degreeId)
                        ->when($ignoreUserId, fn ($q) => $q->where('id_user', '!=', $ignoreUserId))
                        ->exists();

                    if ($taken) {
                        $validator->errors()->add(
                            'load_degree',
                            'Existen grados que ya están asignados a otro/a psicoorientador/a.'
                        );
                        return;
                    }
                }
                return;
            }

            if ($roleName !== 'docente') {
                return;
            }

            $groups = $this->input('groups', []);
            $groupsAsig = $this->input('groups_asig', []);
            $areaIds = $this->input('area_id', []);

            // 1) Validar que los grupos asignados por área estén dentro del listado de grupos del docente.
            foreach ($groupsAsig as $areaId => $assignedGroups) {
                foreach ((array) $assignedGroups as $groupId) {
                    if (!in_array((int) $groupId, array_map('intval', $groups), true)) {
                        $validator->errors()->add(
                            "groups_asig.$areaId",
                            'Los grupos asignados a cada área deben coincidir con los grupos seleccionados.'
                        );
                        break 2;
                    }
                }
            }

            // 2) Validar que no se duplique (área, grupo) con otro docente.
            $existing = Users_teacher::where('number_documment', $this->input('number_documment'))->first();
            $ignoreTeacherId = $existing?->id;

            foreach ((array) $areaIds as $areaId) {
                if (!isset($groupsAsig[$areaId])) {
                    continue;
                }
                foreach ((array) $groupsAsig[$areaId] as $groupId) {
                    $conflict = Teachers_areas_group::where('id_area', $areaId)
                        ->where('id_group', $groupId)
                        ->when($ignoreTeacherId, fn ($q) => $q->where('id_teacher', '!=', $ignoreTeacherId))
                        ->exists();

                    if ($conflict) {
                        $validator->errors()->add(
                            "groups_asig.$areaId",
                            'Hay áreas ya impartidas en uno o más grupos por otro docente.'
                        );
                        return;
                    }
                }
            }
        });
    }
}

