<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\MiniCurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EdicaoController extends Controller
{
    public function index(): View
    {
        return view('admin.edicoes.index', [
            'edicoes' => Edicao::query()->orderByDesc('data_inicio')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.edicoes.form', [
            'edicao' => new Edicao([
                'tipo'             => 'geral',
                'status'           => 'futura',
                'taxas'            => Edicao::TAXAS_DEFAULT,
                'cor_primaria'     => '#0f4c81',
                'cor_secundaria'   => '#f37021',
                'presidente_nome'  => 'JOSÉ JANUÁRIO, PhD.',
                'presidente_titulo'=> 'O PRESIDENTE',
            ]),
            'tipos'  => Edicao::TIPOS,
            'status' => Edicao::STATUS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('edicoes', 'public');
        }

        $edicao = Edicao::create($data);

        return redirect()->route('admin.edicoes.show', $edicao)
            ->with('success', 'Edição "' . $edicao->nome . '" criada.');
    }

    public function show(Edicao $edicao): View
    {
        return view('admin.edicoes.show', [
            'edicao'       => $edicao,
            'miniCursos'   => $edicao->miniCursos()->orderBy('ordem')->get(),
            'inscricoesTotais' => $edicao->inscricoes()->count(),
            'submissoesTotais' => $edicao->submissoes()->count(),
        ]);
    }

    public function edit(Edicao $edicao): View
    {
        return view('admin.edicoes.form', [
            'edicao' => $edicao,
            'tipos'  => Edicao::TIPOS,
            'status' => Edicao::STATUS,
        ]);
    }

    public function update(Request $request, Edicao $edicao): RedirectResponse
    {
        $data = $this->validated($request, $edicao);

        if ($request->boolean('remover_banner') && $edicao->banner_path) {
            Storage::disk('public')->delete($edicao->banner_path);
            $data['banner_path'] = null;
        } elseif ($request->hasFile('banner')) {
            if ($edicao->banner_path) {
                Storage::disk('public')->delete($edicao->banner_path);
            }
            $data['banner_path'] = $request->file('banner')->store('edicoes', 'public');
        }

        $edicao->update($data);

        return redirect()->route('admin.edicoes.show', $edicao)
            ->with('success', 'Edição actualizada.');
    }

    public function destroy(Edicao $edicao): RedirectResponse
    {
        if ($edicao->inscricoes()->exists() || $edicao->submissoes()->exists()) {
            return back()->with('error', 'Não é possível remover: existem inscrições ou submissões associadas.');
        }
        if ($edicao->banner_path) {
            Storage::disk('public')->delete($edicao->banner_path);
        }
        $edicao->delete();

        return redirect()->route('admin.edicoes.index')->with('success', 'Edição removida.');
    }

    public function activar(Edicao $edicao): RedirectResponse
    {
        $edicao->update(['status' => 'actual']);
        return back()->with('success', '"' . $edicao->nome_curto . '" definida como edição actual.');
    }

    // ---------- Mini-cursos ----------

    public function miniCursoStore(Request $request, Edicao $edicao): RedirectResponse
    {
        $data = $request->validate([
            'chave'     => ['required', 'string', 'max:80', 'regex:/^[a-z0-9_\-]+$/'],
            'dia_label' => ['required', 'string', 'max:80'],
            'hora'      => ['required', 'string', 'max:20'],
            'local'     => ['required', 'string', 'max:80'],
            'tema'      => ['required', 'string', 'max:120'],
            'titulo'    => ['required', 'string', 'max:400'],
            'prelector' => ['nullable', 'string', 'max:300'],
            'moderador' => ['nullable', 'string', 'max:200'],
            'ordem'     => ['nullable', 'integer'],
        ]);

        if ($edicao->miniCursos()->where('chave', $data['chave'])->exists()) {
            return back()->withErrors(['chave' => 'Já existe um mini-curso com esta chave nesta edição.'])->withInput();
        }

        $data['edicao_id'] = $edicao->id;
        $data['ordem'] = $data['ordem'] ?? (($edicao->miniCursos()->max('ordem') ?? 0) + 1);
        $data['activo'] = true;

        MiniCurso::create($data);

        return back()->with('success', 'Mini-curso adicionado.');
    }

    public function miniCursoUpdate(Request $request, Edicao $edicao, MiniCurso $miniCurso): RedirectResponse
    {
        abort_unless($miniCurso->edicao_id === $edicao->id, 404);

        $data = $request->validate([
            'dia_label' => ['required', 'string', 'max:80'],
            'hora'      => ['required', 'string', 'max:20'],
            'local'     => ['required', 'string', 'max:80'],
            'tema'      => ['required', 'string', 'max:120'],
            'titulo'    => ['required', 'string', 'max:400'],
            'prelector' => ['nullable', 'string', 'max:300'],
            'moderador' => ['nullable', 'string', 'max:200'],
            'ordem'     => ['nullable', 'integer'],
            'activo'    => ['nullable', 'boolean'],
        ]);
        $data['activo'] = (bool) ($data['activo'] ?? false);

        $miniCurso->update($data);
        return back()->with('success', 'Mini-curso actualizado.');
    }

    public function miniCursoDestroy(Edicao $edicao, MiniCurso $miniCurso): RedirectResponse
    {
        abort_unless($miniCurso->edicao_id === $edicao->id, 404);
        $miniCurso->delete();
        return back()->with('success', 'Mini-curso removido.');
    }

    // ---------- Helpers ----------

    private function validated(Request $request, ?Edicao $edicao = null): array
    {
        $id = $edicao?->id;

        $rules = [
            'numero_romano'              => ['required', 'string', 'max:10'],
            'numero_inteiro'             => ['required', 'integer', 'min:1', 'max:999'],
            'slug'                       => ['required', 'string', 'max:80', 'alpha_dash', 'unique:edicoes,slug,' . $id],
            'nome'                       => ['required', 'string', 'max:200'],
            'nome_curto'                 => ['nullable', 'string', 'max:80'],
            'tipo'                       => ['required', 'in:' . implode(',', array_keys(Edicao::TIPOS))],
            'ano'                        => ['required', 'integer', 'min:2000', 'max:2099'],
            'lema'                       => ['nullable', 'string', 'max:500'],
            'descricao'                  => ['nullable', 'string', 'max:2000'],
            'data_inicio'                => ['required', 'date'],
            'data_fim'                   => ['required', 'date', 'after_or_equal:data_inicio'],
            'local'                      => ['nullable', 'string', 'max:200'],
            'inscricao_inicio'           => ['nullable', 'date'],
            'inscricao_fim'              => ['nullable', 'date', 'after_or_equal:inscricao_inicio'],
            'submissao_inicio'           => ['nullable', 'date'],
            'submissao_fim'              => ['nullable', 'date', 'after_or_equal:submissao_inicio'],
            'trabalhos_admitidos_inicio' => ['nullable', 'date'],
            'trabalhos_admitidos_fim'    => ['nullable', 'date'],
            'presidente_nome'            => ['nullable', 'string', 'max:200'],
            'presidente_titulo'          => ['nullable', 'string', 'max:60'],
            'status'                     => ['required', 'in:' . implode(',', array_keys(Edicao::STATUS))],
            'cor_primaria'               => ['nullable', 'string', 'max:9'],
            'cor_secundaria'             => ['nullable', 'string', 'max:9'],
            'banner'                     => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'mostrar_no_arquivo'         => ['nullable', 'boolean'],
            'taxa_docente_part'          => ['nullable', 'integer', 'min:0'],
            'taxa_docente_mini'          => ['nullable', 'integer', 'min:0'],
            'taxa_estudante_part'        => ['nullable', 'integer', 'min:0'],
            'taxa_estudante_mini'        => ['nullable', 'integer', 'min:0'],
            'taxa_publico_part'          => ['nullable', 'integer', 'min:0'],
            'taxa_publico_mini'          => ['nullable', 'integer', 'min:0'],
        ];

        $data = $request->validate($rules);

        $data['slug']               = Str::slug($data['slug']);
        $data['nome_curto']         = $data['nome_curto'] ?? $data['nome'];
        $data['mostrar_no_arquivo'] = (bool) ($request->input('mostrar_no_arquivo') ?? true);

        $data['taxas'] = [
            'docente'   => [
                'participacao' => (int) ($data['taxa_docente_part']   ?? 10000),
                'mini_curso'   => (int) ($data['taxa_docente_mini']   ?? 5000),
            ],
            'estudante' => [
                'participacao' => (int) ($data['taxa_estudante_part'] ?? 2000),
                'mini_curso'   => (int) ($data['taxa_estudante_mini'] ?? 3000),
            ],
            'publico'   => [
                'participacao' => (int) ($data['taxa_publico_part']   ?? 10000),
                'mini_curso'   => (int) ($data['taxa_publico_mini']   ?? 5000),
            ],
        ];

        unset(
            $data['taxa_docente_part'], $data['taxa_docente_mini'],
            $data['taxa_estudante_part'], $data['taxa_estudante_mini'],
            $data['taxa_publico_part'], $data['taxa_publico_mini'],
            $data['banner']
        );

        return $data;
    }
}
