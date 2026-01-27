@extends('layouts.app')

@section('title','Cursos')
@section('page_title','Cursos')
@section('page_hint','Administrá cursos y cohortes')

@section('content')
  <style>
    .ic-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-start;margin-bottom:12px}
    .ic-list{display:flex;flex-direction:column;gap:10px}
    .ic-item{display:flex;gap:12px;align-items:flex-start;justify-content:space-between;border:1px solid var(--border);border-radius:16px;background:#fff;padding:12px}
    .ic-left{display:flex;gap:12px;align-items:flex-start;min-width:0;flex:1}
    .ic-thumb{width:72px;height:54px;border-radius:12px;border:1px solid var(--border);background:#f1f5f9;overflow:hidden;flex:0 0 auto;display:flex;align-items:center;justify-content:center;font-size:12px;color:#64748b}
    .ic-thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .ic-main{min-width:0}
    .ic-title{font-weight:900;line-height:1.15;margin:0 0 4px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .ic-meta{font-size:12px;color:#64748b;margin:0 0 6px}
    .ic-desc{font-size:13px;color:#0f172a;opacity:.9;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .ic-right{display:flex;flex-direction:column;gap:8px;align-items:flex-end;flex:0 0 auto}
    .ic-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:800;border:1px solid var(--border);background:#f8fafc;color:#0f172a}
    .ic-badge.on{background:rgba(34,197,94,.10);border-color:rgba(34,197,94,.25);color:#166534}
    .ic-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}
    .ic-actions .btn{padding:8px 10px;border-radius:12px}
    .ic-small{font-size:12px;color:#64748b}
  </style>

  <div class="ic-toolbar">
    <a class="btn btn-primary" href="{{ route('admin.academic.courses.create') }}">+ Crear curso</a>
   
    

  </div>

  <div class="ic-list">
    @forelse($courses as $c)
      <div class="ic-item">
        <div class="ic-left">
          <div class="ic-thumb">
            @if(!empty($c->cover_path))
              <img src="{{ asset('storage/'.$c->cover_path) }}" alt="Carátula">
            @else
              sin img
            @endif
          </div>

          <div class="ic-main">
            <div class="ic-title" title="{{ $c->title }}">{{ $c->title }}</div>
            <div class="ic-meta">
              <strong>{{ $c->code }}</strong>
              @if(!empty($c->type)) · {{ ucfirst($c->type) }} @endif
              @if(!empty($c->modality)) · {{ $c->modality }} @endif
            </div>

            @if(!empty($c->description))
              <p class="ic-desc">{{ $c->description }}</p>
            @endif

            <div class="ic-small" style="margin-top:6px">
              @if(!empty($c->resolution_number)) <span><strong>Res.:</strong> {{ $c->resolution_number }}</span> @endif
              @if(!empty($c->expediente_number))
                <span style="margin-left:10px"><strong>Expte.:</strong> {{ $c->expediente_number }}</span>
              @endif
              @if(!empty($c->normative_pdf_path))
                <span style="margin-left:10px"><a href="{{ asset('storage/'.$c->normative_pdf_path) }}" target="_blank" rel="noopener">Ver PDF</a></span>
              @endif
            </div>
          </div>
        </div>

        <div class="ic-right">
          <span class="ic-badge {{ $c->is_active ? 'on' : '' }}">
            {{ $c->is_active ? 'Activo' : 'Inactivo' }}
          </span>

          <div class="ic-actions">
            <a class="btn btn-soft" href="{{ route('admin.academic.courses.edit', $c) }}">Editar</a>
            <a class="btn btn-ghost" href="{{ route('admin.academic.cohorts.create', $c) }}">Crear cohorte</a>
 

          </div>
        </div>
      </div>
    @empty
      <div style="padding:12px;border:1px solid var(--border);border-radius:14px;background:#fff;">
        No hay cursos.
      </div>
    @endforelse
  </div>

  <div style="margin-top:12px;">
    {{ $courses->links() }}
  </div>
@endsection
