<?php

namespace App\Providers;

use App\Models\Configuracao;
use App\Models\Inscricao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('pt');
        View::share('branding', $this->loadBranding());

        // O parâmetro {mco} nas rotas resolve para o modelo Inscricao
        Route::model('mco', Inscricao::class);
    }

    private function loadBranding(): array
    {
        $empty = ['logo' => null, 'simbolo' => null, 'banner' => null];

        try {
            if (! Schema::hasTable('configuracoes')) {
                return $empty;
            }

            return [
                'logo'    => Configuracao::imageUrl('logo_path'),
                'simbolo' => Configuracao::imageUrl('simbolo_path'),
                'banner'  => Configuracao::imageUrl('banner_path'),
            ];
        } catch (\Throwable $e) {
            return $empty;
        }
    }
}
