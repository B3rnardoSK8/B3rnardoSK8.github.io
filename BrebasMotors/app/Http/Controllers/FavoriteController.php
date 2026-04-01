<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $cars = $user
            ->favoriteCars()
            ->orderByPivot('created_at', 'desc')
            ->paginate(9);

        return view('account.favorites', [
            'cars' => $cars,
        ]);
    }

    public function toggle(Request $request, Car $car)
    {
        /** @var User $user */
        $user = Auth::user();

        $isFavorite = $user->favoriteCars()
            ->where('cars.id', $car->id)
            ->exists();

        if ($isFavorite) {
            $user->favoriteCars()->detach($car->id);
            $favoriteNow = false;
            $status = 'Carro removido dos favoritos.';
        } else {
            $user->favoriteCars()->attach($car->id);
            $favoriteNow = true;
            $status = 'Carro adicionado aos favoritos.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'is_favorite' => $favoriteNow,
            ]);
        }

        return back()->with('status', $status);
    }
}
