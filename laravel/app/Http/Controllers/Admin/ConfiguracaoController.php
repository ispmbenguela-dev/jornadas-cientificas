<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConfiguracaoController extends Controller
{
    private const CAMPOS = [
        'logo'    => 'logo_path',
        'simbolo' => 'simbolo_path',
        'banner'  => 'banner_path',
    ];

    private const PASTA = 'branding';

    public function index(): View
    {
        $configs = Configuracao::all()->keyBy('chave');
        return view('admin.configuracoes.index', compact('configs'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'logo'                     => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'simbolo'                  => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
            'banner'                   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'submissao_prazo'          => ['nullable', 'date'],
            'inscricao_prazo'          => ['nullable', 'date'],
            'inscricao_forcar_fechado' => ['nullable', 'boolean'],
        ], [
            'image' => 'O ficheiro deve ser uma imagem válida.',
            'max'   => 'A imagem excede o tamanho máximo permitido.',
            'date'  => 'A data não é válida.',
        ]);

        foreach (self::CAMPOS as $field => $key) {
            if ($request->boolean("remover_$field")) {
                $this->deleteCurrent($key);
                Configuracao::set($key, null, 'image');
                continue;
            }

            if ($request->hasFile($field)) {
                $this->deleteCurrent($key);
                $path = $this->storeWithOriginalName($request->file($field));
                Configuracao::set($key, $path, 'image');
            }
        }

        if ($request->filled('submissao_prazo')) {
            Configuracao::set('submissao_prazo', $request->input('submissao_prazo'), 'date');
        }

        if ($request->filled('inscricao_prazo')) {
            Configuracao::set('inscricao_prazo', $request->input('inscricao_prazo'), 'date');
        }

        Configuracao::set(
            'inscricao_forcar_fechado',
            $request->boolean('inscricao_forcar_fechado') ? '1' : '0',
            'boolean'
        );

        return back()->with('success', 'Configurações actualizadas.');
    }

    private function storeWithOriginalName(UploadedFile $file): string
    {
        $disk = Storage::disk('public');

        $originalBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $base         = Str::slug($originalBase) ?: 'imagem';

        $filename = "{$base}.{$extension}";
        $i = 2;
        while ($disk->exists(self::PASTA . "/{$filename}")) {
            $filename = "{$base}-{$i}.{$extension}";
            $i++;
        }

        return $file->storeAs(self::PASTA, $filename, 'public');
    }

    private function deleteCurrent(string $key): void
    {
        $path = Configuracao::get($key);
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
