<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const BLOCKED_ROLES = ['student'];

    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $caBundle = config('services.sigam.ca_bundle');
        $verify   = is_string($caBundle) && is_file($caBundle) ? $caBundle : true;

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->connectTimeout(15)
                ->timeout((int) config('services.sigam.timeout', 25))
                ->withOptions(['verify' => $verify])
                ->post(config('services.sigam.verify_url'), [
                    'email'    => $credentials['email'],
                    'password' => $credentials['password'],
                ]);
        } catch (ConnectionException $e) {
            Log::warning('SIGAM verify connection error', ['msg' => $e->getMessage()]);
            return back()->withErrors([
                'email' => 'Não foi possível contactar o serviço SIGAM. Verifique a sua ligação e tente novamente.',
            ])->onlyInput('email');
        } catch (\Throwable $e) {
            Log::error('SIGAM verify unexpected error', ['msg' => $e->getMessage()]);
            return back()->withErrors([
                'email' => 'Erro inesperado a contactar o SIGAM.',
            ])->onlyInput('email');
        }

        $data     = $response->json() ?? [];
        $verified = $response->successful() && (($data['verified'] ?? false) === true);

        if (! $verified) {
            $message = $data['message'] ?? 'Credenciais inválidas.';
            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        if ($this->hasBlockedRole($data)) {
            return back()->withErrors([
                'email' => 'O acesso ao painel não está disponível para utilizadores com perfil de estudante.',
            ])->onlyInput('email');
        }

        $user = $this->resolveLocalUser($credentials['email'], $data);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function hasBlockedRole(array $data): bool
    {
        $roles = $data['user']['roles']
            ?? $data['data']['roles']
            ?? $data['roles']
            ?? [];

        if (! is_array($roles)) {
            return false;
        }

        foreach ($roles as $role) {
            $name = is_array($role) ? ($role['name'] ?? null) : null;
            if (is_string($name) && in_array(strtolower($name), self::BLOCKED_ROLES, true)) {
                return true;
            }
        }

        return false;
    }

    private function resolveLocalUser(string $email, array $data): User
    {
        $remote = $data['user'] ?? $data['data'] ?? [];
        $name   = $remote['name']
            ?? $remote['nome']
            ?? $remote['full_name']
            ?? $data['name']
            ?? Str::title(Str::before($email, '@'));

        $user = User::firstOrNew(['email' => $email]);

        $user->name              = $name;
        $user->email_verified_at = $user->email_verified_at ?? now();

        if (! $user->exists) {
            $user->password = Hash::make(Str::random(48));
        }

        $user->save();

        return $user;
    }
}
