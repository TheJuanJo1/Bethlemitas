<?php

namespace App\Http\Requests\Academic;

use App\Models\Teachers_areas_group;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');
        
        // 🔹 Determinamos el rol OBJETIVO (el que viene en el request)
        $roleId = $this->input('role');
        $targetRole = Role::find($roleId);
        $targetRoleName = $targetRole?->name;

        $base = [
            'role' => ['required', 'exists:roles,id'],
            'number_documment' => [
                'required',
                'digits_between:1,20',
                Rule::unique('users_teachers', 'number_documment')->ignore($id),
            ],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users_teachers', 'email')->ignore($id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ];

        // 🔹 Reglas según el rol al que se quiere cambiar
        if ($targetRoleName === 'docente') {
            return $base + [
                'group_director' => [
                    'nullable',
                    Rule::unique('users_teachers', 'group_director')->ignore($id),
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

        if ($targetRoleName === 'psicoorientador') {
            return $base + [
                'load_degree' => ['required', 'array', 'min:1'],
                'load_degree.*' => ['integer', 'exists:degrees,id'],
            ];
        }

        return $base;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $id = (int) $this->route('id');
            $roleId = $this->input('role');
            $targetRole = Role::find($roleId);
            $targetRoleName = $targetRole?->name;

            if ($targetRoleName === 'psicoorientador') {
                foreach ((array) $this->input('load_degree', []) as $degreeId) {
                    $taken = Users_load_degree::where('id_degree', $degreeId)
                        ->where('id_user', '!=', $id)
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

            if ($targetRoleName !== 'docente') {
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
            foreach ((array) $areaIds as $areaId) {
                if (!isset($groupsAsig[$areaId])) {
                    continue;
                }
                foreach ((array) $groupsAsig[$areaId] as $groupId) {
                    $conflict = \DB::table('teachers_areas_groups as tag')
                        ->join('users_teachers as u', 'u.id', '=', 'tag.id_teacher')
                        ->join('areas as a', 'a.id', '=', 'tag.id_area')
                        ->join('groups as g', 'g.id', '=', 'tag.id_group')
                        ->select('u.name', 'u.last_name', 'a.name_area', 'g.group')
                        ->where('tag.id_area', $areaId)
                        ->where('tag.id_group', $groupId)
                        ->where('tag.id_teacher', '!=', $id)
                        ->first();

                    if ($conflict) {
                        $validator->errors()->add(
                            "groups_asig.$areaId",
                            "El área '{$conflict->name_area}' ya la imparte el docente {$conflict->name} {$conflict->last_name} en el grupo {$conflict->group}."
                        );
                        return;
                    }
                }
            }
        });
    }
}
