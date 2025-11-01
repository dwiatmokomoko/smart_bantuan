<?php
// app/Repositories/FoUserRepository.php
namespace App\Repositories;

use App\Models\User;

class FoUserRepository
{
    public function getAll()
    {
        // hanya role user (warga)
        return User::query()
            ->where('role', 'user')
            ->select(['id','name','email','alamat','no_hp','created_at'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getById(int $id)
    {
        return User::where('role','user')->findOrFail($id, ['id','name','email','alamat','no_hp','created_at']);
    }

    public function destroy(int $id)
    {
        return User::where('role','user')->where('id',$id)->delete();
    }
}
