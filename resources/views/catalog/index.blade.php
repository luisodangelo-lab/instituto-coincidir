@extends('layouts.app')

@section('title', 'Cursos')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Cursos disponibles</h1>
    <div class="text-muted small">Elegí una cohorte y preinscribite</div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Revisá lo siguiente:</div>
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  @forelse($courses as $course)
    <div class="card mb-3 shadow-sm">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
          <div>
            <div class="d-flex align-items-center gap-2">
              <div class="fw-semibold">{{ $course->title }}</div>
              @if($course->type ?? null)
                <span class="badge text-bg-secondary">{{ $course->type }}</span>
              @endif
            </div>
            <div class="text-muted small">{{ $course->code }}</div>
            @if($course->description)
              <div class="mt-2">{{ \Illuminate\Support\Str::limit($course->description, 160) }}</div>
            @endif
          </div>

          <a class="btn btn-outline-primary"
             href="{{ route('catalog.show', $course->code) }}">
            Ver detalles
          </a>
        </div>

        <hr class="my-3">

        @if($course->cohorts->count() === 0)
          <div class="text-muted">No hay cohortes abiertas por el momento.</div>
        @else
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Cohorte</th>
                  <th>Inicio</th>
                  <th>Fin</th>
                  <th class="text-end">Precio</th>
                  <th class="text-end">Cuotas</th>
                  <th class="text-end"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($course->cohorts as $cohort)
                  <tr>
                    <td class="fw-semibold">{{ $cohort->name }}</td>
                    <td>{{ $cohort->start_date ? \Carbon\Carbon::parse($cohort->start_date)->format('d/m/Y') : '—' }}</td>
                    <td>{{ $cohort->end_date ? \Carbon\Carbon::parse($cohort->end_date)->format('d/m/Y') : '—' }}</td>
                    <td class="text-end">
                      $ {{ number_format((float)$cohort->price_total, 0, ',', '.') }}
                    </td>
                    <td class="text-end">
                      {{ (int)$cohort->installments_count }}
                    </td>
                    <td class="text-end">
                      @auth
                        <form method="POST" action="{{ route('preinscriptions.store', $cohort) }}" class="d-inline">
                          @csrf
                          <button class="btn btn-primary btn-sm">
                            Preinscribirme
                          </button>
                        </form>
                      @else
                        <a class="btn btn-primary btn-sm"
                           href="{{ route('login', ['next' => route('catalog.show', $course->code)]) }}">
                          Ingresar para preinscribirme
                        </a>
                      @endauth
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  @empty
    <div class="alert alert-info">
      No hay cursos activos por el momento.
    </div>
  @endforelse

</div>
@endsection
