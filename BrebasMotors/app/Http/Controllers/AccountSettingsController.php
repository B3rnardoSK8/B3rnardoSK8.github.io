<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'remove_profile_photo' => ['nullable', 'boolean'],
        ]);

        $shouldRemovePhoto = $request->boolean('remove_profile_photo');

        if ($shouldRemovePhoto) {
            if (!empty($user->profile_photo_path)) {
                $oldPath = public_path($user->profile_photo_path);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $validated['profile_photo_path'] = null;
        }

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
        unset($validated['remove_profile_photo']);

        $user->fill($validated);
        $user->save();

        return redirect()->route('account.settings')->with('status', 'Dados atualizados com sucesso.');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validateWithBag('deleteAccount', [
            'delete_email' => ['required', 'email'],
            'delete_password' => ['required', 'string'],
        ]);

        if (
            strtolower(trim($validated['delete_email'])) !== strtolower($user->email)
            || !Hash::check($validated['delete_password'], $user->password)
        ) {
            return back()->withErrors([
                'delete_credentials' => 'Email ou palavra-passe incorretos. A conta não foi eliminada.',
            ], 'deleteAccount')->withInput();
        }

        if (!empty($user->profile_photo_path)) {
            $photoPath = public_path($user->profile_photo_path);
            if (is_file($photoPath)) {
                @unlink($photoPath);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('status', 'A sua conta foi eliminada com sucesso.');
    }
}
