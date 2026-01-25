@extends('layouts.app')
@section('title','Cursos')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 mb-0">Cursos</h1>
      <div class="text-muted small">Administrá cursos y cohortes</div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-primary btn-sm" href="{{ route('admin.academic.courses.create') }}">+ Crear curso</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.academic.enrollments.create') }}">+ Nueva matrícula</a>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:90px;">Carátula</th>
            <th>Curso</th>
            <th style="width:260px;">Normativa / PDF</th>
            <th style="width:110px;">Activo</th>
            <th class="text-end" style="width:260px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($courses as $c)
            <tr>
              <td>
                @if(!empty($c->cover_path))
                  <img src="{{ asset('storage/'.$c->cover_path) }}"
                       class="rounded border"
                       style="width:72px;height:48px;object-fit:cover;">
                @else
                  <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                       style="width:72px;height:48px;font-size:12px;color:#777;">
                    sin img
                  </div>
                @endif
              </td>

              <td>
                <div class="fw-semibold">{{ $c->title }}</div>
                <div class="text-muted small">{{ $c->code }} · {{ ucfirst($c->type ?? 'curso') }}</div>
                @if(!empty($c->description))
                  <div class="small mt-1">{{ \Illuminate\Support\Str::limit($c->description, 120) }}</div>
                @endif
              </td>

              <td>
                <div class="small">
                  @if(!empty($c->disposition_number)) <div><span class="text-muted">Disp.:</span> {{ $c->disposition_number }}</div> @endif
                  @if(!empty($c->resolution_number)) <div><span class="text-muted">Res.:</span> {{ $c->resolution_number }}</div> @endif
                  @if(!empty($c->expediente_number)) <div><span class="text-muted">Expte.:</span> {{ $c->expediente_number }}</div> @endif
                </div>
                @if(!empty($c->brochure_path))
                  <a class="small" href="{{ asset('storage/'.$c->brochure_path) }}" target="_blank" rel="noopener">Ver PDF</a>
                @endif
              </td>

              <td>
                @if($c->is_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>

              <td class="text-end">
                <div class="btn-group btn-group-sm" role="group">
                  <a class="btn btn-outline-primary" href="{{ route('admin.academic.courses.edit', $c) }}">Editar</a>
                  <a class="btn btn-outline-secondary" href="{{ route('admin.academic.cohorts.create', $c) }}">Crear cohorte</a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-muted">No hay cursos.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $courses->links() }}
  </div>

</div>
@endsection
