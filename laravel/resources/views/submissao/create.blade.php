@extends('layouts.app')

@section('title', 'Submeter Trabalho — XI Jornada ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Submissão de trabalhos</span>
            <h2 class="section-title">Submeter <span class="text-accent">artigo científico</span></h2>
            <p class="section-lead mx-auto" style="max-width: 760px">
                Carregue o ficheiro do seu trabalho (PDF, DOC ou DOCX, até 10 MB).
                A Comissão Científica avaliará e publicará a lista dos trabalhos
                admitidos entre 18 e 24 de Maio de 2026.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="form-card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Verifique os campos:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('submissao.store') }}" enctype="multipart/form-data" class="row g-3">
                        @csrf

                        <div class="col-12">
                            <label class="form-label">Título do trabalho *</label>
                            <input type="text" name="titulo" value="{{ old('titulo') }}" class="form-control" required maxlength="255" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Autor principal (expositor) *</label>
                            <input type="text" name="autor_principal" value="{{ old('autor_principal') }}" class="form-control" required maxlength="160" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Coautores</label>
                            <input type="text" name="coautores" value="{{ old('coautores') }}" class="form-control" maxlength="1000" placeholder="Separados por vírgula" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">E-mail de contacto *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required maxlength="160" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="tel" name="telefone" value="{{ old('telefone') }}" class="form-control" maxlength="40" />
                        </div>

                        <div class="col-md-7">
                            <label class="form-label">Instituição *</label>
                            <input type="text" name="instituicao" value="{{ old('instituicao') }}" class="form-control" required maxlength="200" />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Área temática</label>
                            <select name="area_tematica" class="form-select">
                                <option value="">— Seleccione —</option>
                                @foreach ($areas as $a)
                                    <option value="{{ $a }}" @selected(old('area_tematica') === $a)>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Resumo (até 250 palavras) *</label>
                            <textarea name="resumo" rows="6" class="form-control" required maxlength="2500" placeholder="Apresente em até 250 palavras o objectivo, metodologia e principais conclusões.">{{ old('resumo') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Ficheiro do artigo (PDF/DOC/DOCX · até 10 MB) *</label>
                            <input type="file" name="ficheiro" class="form-control" accept=".pdf,.doc,.docx" required />
                            <small class="text-muted">Máx. 10 páginas, espaçamento duplo, margens 2.5&nbsp;cm, fonte 12, normas APA 7.ª Ed.</small>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-cta">
                                <i class="bi bi-cloud-upload"></i> Submeter trabalho
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-ghost">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
