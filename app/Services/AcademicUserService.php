<?php

namespace App\Services;

use App\Models\State;
use App\Models\Users_teacher;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AcademicUserService
{
    public function storeDocente(array $data): Users_teacher
    {
        return DB::transaction(function () use ($data) {
            $stateActive = State::where('state', 'activo')->firstOrFail();

            $user = Users_teacher::where('number_documment', $data['number_documment'])->first();
            if (!$user) {
                $user = new Users_teacher();
                $user->password = bcrypt($data['number_documment']);
            }

            $user->number_documment = $data['number_documment'];
            $user->name = $data['name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->group_director = $data['group_director'] ?? null;
            $user->id_state = $stateActive->id;
            $user->save();

            if (!$user->hasRole('docente')) {
                $user->assignRole('docente');
            }

            $this->syncDocenteAssignments(
                $user->id,
                $data['areas'],
                $data['groups'],
                $this->buildAreaGroupAssignments($data['groups_asig'] ?? [], $data['area_id'] ?? [])
            );

            return $user;
        });
    }

    public function storePsicoorientador(array $data): Users_teacher
    {
        return DB::transaction(function () use ($data) {
            $stateActive = State::where('state', 'activo')->firstOrFail();

            $user = Users_teacher::where('number_documment', $data['number_documment'])->first();
            if (!$user) {
                $user = new Users_teacher();
                $user->password = bcrypt($data['number_documment']);
            }

            $user->number_documment = $data['number_documment'];
            $user->name = $data['name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->id_state = $stateActive->id;
            $user->save();

            if (!$user->hasRole('psicoorientador')) {
                $user->assignRole('psicoorientador');
            }

            $this->syncPsicoDegrees($user->id, $data['load_degree']);

            return $user;
        });
    }

    public function updateUser(Users_teacher $user, array $data): bool
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. Actualizar datos básicos
            $user->number_documment = $data['number_documment'];
            $user->name = $data['name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            
            if (!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }

            // 2. Gestionar cambio de Rol
            $newRole = Role::findOrFail($data['role']); // Obtenemos el nuevo rol por ID
            $oldRoleName = $user->roles->first()?->name;

            // Si el rol cambió, limpiamos asignaciones anteriores
            if ($oldRoleName !== $newRole->name) {
                $this->clearAllAssignments($user->id);
                $user->syncRoles([$newRole->name]);
            }

            // 3. Lógica específica según el NUEVO rol
            if ($newRole->name === 'docente') {
                $user->group_director = $data['group_director'] ?? null;
                $this->syncDocenteAssignments(
                    $user->id,
                    $data['areas'] ?? [],
                    $data['groups'] ?? [],
                    $this->buildAreaGroupAssignments($data['groups_asig'] ?? [], $data['area_id'] ?? [])
                );
            } elseif ($newRole->name === 'psicoorientador') {
                $user->group_director = null;
                $this->syncPsicoDegrees($user->id, $data['load_degree'] ?? []);
            }

            $user->save();
            return true;
        });
    }

    private function clearAllAssignments(int $userId): void
    {
        DB::table('users_load_areas')->where('id_user_teacher', $userId)->delete();
        DB::table('users_load_groups')->where('id_user_teacher', $userId)->delete();
        DB::table('teachers_areas_groups')->where('id_teacher', $userId)->delete();
        DB::table('users_load_degrees')->where('id_user', $userId)->delete();
    }

    private function syncPsicoDegrees(int $userId, array $degreeIds): void
    {
        DB::table('users_load_degrees')->where('id_user', $userId)->delete();

        $rows = array_map(fn ($degreeId) => [
            'id_user' => $userId,
            'id_degree' => (int) $degreeId,
        ], $degreeIds);

        if (!empty($rows)) {
            DB::table('users_load_degrees')->insert($rows);
        }
    }

    private function syncDocenteAssignments(int $teacherId, array $areaIds, array $groupIds, array $areaGroupPairs): void
    {
        foreach ($areaGroupPairs as $pair) {
            $conflict = DB::table('teachers_areas_groups as tag')
                ->join('users_teachers as u', 'u.id', '=', 'tag.id_teacher')
                ->join('areas as a', 'a.id', '=', 'tag.id_area')
                ->join('groups as g', 'g.id', '=', 'tag.id_group')
                ->select('u.name','u.last_name','a.name_area','g.group')
                ->where('tag.id_area', $pair['id_area'])
                ->where('tag.id_group', $pair['id_group'])
                ->where('tag.id_teacher', '!=', $teacherId)
                ->first();

            if ($conflict) {
                throw new \Exception(
                    "El área {$conflict->name_area} ya está asignada al docente {$conflict->name} {$conflict->last_name} en el grupo {$conflict->group}."
                );
            }
        }

        DB::table('users_load_areas')->where('id_user_teacher', $teacherId)->delete();
        DB::table('users_load_groups')->where('id_user_teacher', $teacherId)->delete();
        DB::table('teachers_areas_groups')->where('id_teacher', $teacherId)->delete();

        $areaRows = array_map(fn ($areaId) => [
            'id_user_teacher' => $teacherId,
            'id_area' => (int) $areaId,
        ], $areaIds);

        $groupRows = array_map(fn ($groupId) => [
            'id_user_teacher' => $teacherId,
            'id_group' => (int) $groupId,
        ], $groupIds);

        $tagRows = array_map(fn ($pair) => [
            'id_teacher' => $teacherId,
            'id_area' => (int) $pair['id_area'],
            'id_group' => (int) $pair['id_group'],
        ], $areaGroupPairs);

        if (!empty($areaRows)) DB::table('users_load_areas')->insert($areaRows);
        if (!empty($groupRows)) DB::table('users_load_groups')->insert($groupRows);
        if (!empty($tagRows)) DB::table('teachers_areas_groups')->insert($tagRows);
    }

    private function buildAreaGroupAssignments(array $groupsAsig, array $areaIds): array
    {
        $pairs = [];
        foreach ($areaIds as $areaId) {
            if (!isset($groupsAsig[$areaId])) continue;
            foreach ((array) $groupsAsig[$areaId] as $groupId) {
                $pairs[] = [
                    'id_area' => (int) $areaId,
                    'id_group' => (int) $groupId,
                ];
            }
        }
        $unique = [];
        foreach ($pairs as $p) {
            $key = $p['id_area'] . '-' . $p['id_group'];
            $unique[$key] = $p;
        }
        return array_values($unique);
    }
}
