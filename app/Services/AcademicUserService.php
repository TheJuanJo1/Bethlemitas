<?php

namespace App\Services;

use App\Models\State;
use App\Models\Users_teacher;
use Illuminate\Support\Facades\DB;

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

    public function updateDocente(Users_teacher $user, array $data): bool
    {
        return DB::transaction(function () use ($user, $data) {
            $user->fill([
                'number_documment' => $data['number_documment'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'group_director' => $data['group_director'] ?? null,
            ]);

            $dirtyUser = $user->isDirty();
            if ($dirtyUser) {
                $user->save();
            }

            $this->syncDocenteAssignments(
                $user->id,
                $data['areas'],
                $data['groups'],
                $this->buildAreaGroupAssignments($data['groups_asig'] ?? [], $data['area_id'] ?? [])
            );

            return true;
        });
    }

    public function updatePsicoorientador(Users_teacher $user, array $data): bool
    {
        return DB::transaction(function () use ($user, $data) {
            $user->fill([
                'number_documment' => $data['number_documment'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);

            if ($user->isDirty()) {
                $user->save();
            }

            $this->syncPsicoDegrees($user->id, $data['load_degree']);

            return true;
        });
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
        // Mantener las tablas auxiliares, pero centralizar el "source of truth" en teachers_areas_groups.
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

        if (!empty($areaRows)) {
            DB::table('users_load_areas')->insert($areaRows);
        }
        if (!empty($groupRows)) {
            DB::table('users_load_groups')->insert($groupRows);
        }
        if (!empty($tagRows)) {
            DB::table('teachers_areas_groups')->insert($tagRows);
        }
    }

    private function buildAreaGroupAssignments(array $groupsAsig, array $areaIds): array
    {
        $pairs = [];

        foreach ($areaIds as $areaId) {
            if (!isset($groupsAsig[$areaId])) {
                continue;
            }
            foreach ((array) $groupsAsig[$areaId] as $groupId) {
                $pairs[] = [
                    'id_area' => (int) $areaId,
                    'id_group' => (int) $groupId,
                ];
            }
        }

        // Eliminar duplicados por si el front manda repetidos.
        $unique = [];
        foreach ($pairs as $p) {
            $key = $p['id_area'] . '-' . $p['id_group'];
            $unique[$key] = $p;
        }

        return array_values($unique);
    }
}

