<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BackOfficeController extends Controller
{
    private function ensureUsersManagementAccess(): void
    {
        abort_unless((int) (Auth::id() ?? 0) === 1, 403);
    }

    private function ensureAdmin(): void
    {
        abort_unless((int) (Auth::user()?->tipo_id ?? 0) === 1, 403);
    }

    public function carsIndex()
    {
        $this->ensureAdmin();

        $cars = Car::query()
            ->orderByDesc('id')
            ->paginate(15);

        return view('back.cars.index', [
            'cars' => $cars,
        ]);
    }

    public function carsCreate()
    {
        $this->ensureAdmin();

        return view('back.cars.create');
    }

    public function usersIndex()
    {
        $this->ensureUsersManagementAccess();

        $users = User::query()
            ->orderByDesc('id')
            ->paginate(15);

        $tipos = DB::table('tipo')
            ->orderBy('id')
            ->get(['id', 'nome']);

        return view('back.users.index', [
            'users' => $users,
            'tipos' => $tipos,
        ]);
    }

    public function usersUpdateTipo(Request $request, User $user)
    {
        $this->ensureUsersManagementAccess();

        if ((int) (Auth::id() ?? 0) === (int) $user->id) {
            return redirect()
                ->route('back.users.index')
                ->with('error', 'Nao pode alterar o seu proprio cargo.');
        }

        $validated = $request->validate([
            'tipo_id' => ['required', 'integer', 'exists:tipo,id'],
        ]);

        $user->tipo_id = (int) $validated['tipo_id'];
        $user->save();

        return redirect()
            ->route('back.users.index')
            ->with('status', 'Cargo do utilizador atualizado com sucesso.');
    }
}
