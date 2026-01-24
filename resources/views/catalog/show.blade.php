@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container py-4">

  <div class="mb-3">
    <a href="{{ route('catalog.index') }}" class="text-decoration-none">&larr; Volver a cursos</a>
  </div>

  
  @if($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Revisá lo siguiente:</div>
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
          <h1 class="h4 mb-1">{{ $course->title }}</h1>
          <div class="text-muted small">
            {{ $course->code }}
            @if($course->type ?? null)
              • <span class="badge text-bg-secondary">{{ $course->type }}</span>
            @endif
          </div>
        </div>

        @php
  $activeCohort = $course->cohorts->firstWhere('is_active', 1);
@endphp

<div class="d-flex gap-2">
  @php
  $cohorts = $course->cohorts ?? collect();
  $openCohorts = $cohorts->filter(fn($c) => (int) data_get($c, 'is_active', 0) === 1);
@endphp

<div class="d-flex gap-2 align-items-center">
  <a class="btn btn-outline-secondary" href="{{ route('catalog.index') }}">← Volver a cursos</a>

  @if($openCohorts->count() > 0)
    <a class="btn btn-primary" href="{{ route('public.enroll.show', $course->code) }}">
      Inscribirme
    </a>
  @else
    <button class="btn btn-primary" disabled>Inscribirme</button>
  @endif
</div>

</div>

  
      </div>

      @if($course->description)
        <div class="mt-3">
          <div class="fw-semibold mb-1">Descripción</div>
          <div>{{ $course->description }}</div>
        </div>
      @endif

      <hr class="my-4">

      <div class="fw-semibold mb-2">Cohortes abiertas</div>

      @if($course->cohorts->count() === 0)
        <div class="text-muted">No hay cohortes abiertas por el momento.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle">
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
                  <td class="text-end">$ {{ number_format((float)$cohort->price_total, 0, ',', '.') }}</td>
                  <td class="text-end">{{ (int)$cohort->installments_count }}</td>
                  <td class="text-end">
                   <td class="text-end">
  @if(($cohort->is_active ?? 0) == 1)
    <a class="btn btn-primary" href="{{ route('public.enroll.show', $course) }}">
      Inscribirme
    </a>
  @else
    <span class="badge text-bg-secondary">Cerrada</span>
  @endif
</td>


                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="text-muted small mt-2">
          Al preinscribirte, un integrante del equipo se va a contactar para enviarte los datos de pago (transferencia).
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
