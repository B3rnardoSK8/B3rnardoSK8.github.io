<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        return view('account.settings');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'city' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $uploadDir = public_path('resources/images/profiles');

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!empty($user->profile_photo_path)) {
                $oldPath = public_path($user->profile_photo_path);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('profile_photo');
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $validated['profile_photo_path'] = 'resources/images/profiles/' . $filename;
        }

        unset($validated['profile_photo']);

        $user->fill($validated);
        $user->save();

        return redirect()->route('account.settings')->with('status', 'Dados atualizados com sucesso.');
    }
}
