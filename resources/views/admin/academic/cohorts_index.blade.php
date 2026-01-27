@extends('layouts.app')
@section('title','Cohortes')
@section('page_title','Cohortes del curso')
@section('page_hint','Creá, editá y administrá cohortes.')

@section('content')

  {{-- Cabecera clara del curso + imagen --}}
  <div class="card mb-3">
    <div class="card-body d-flex gap-3 align-items-center">
      @if(!empty($course->cover_path))
        <img src="{{ asset('storage/'.$course->cover_path) }}"
             style="width:92px;height:92px;object-fit:cover;border-radius:14px"
             alt="Portada curso">
      @else
        <div style="width:92px;height:92px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
          <span class="text-muted small">sin img</span>
        </div>
      @endif

      <div>
        <div class="fw-semibold" style="font-size:18px;">
          {{ $course->title }}
        </div>
        <div class="text-muted">
          <strong>{{ $course->code }}</strong>
          @if(!empty($course->type)) · {{ ucfirst($course->type) }} @endif
          @if(!empty($course->modality)) · {{ $course->modality }} @endif
        </div>
      </div>

      <div class="ms-auto d-flex gap-2">
        <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Volver</a>
        <a class="btn btn-primary" href="{{ route('admin.academic.cohorts.create', $course) }}">Nueva cohorte</a>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">

      @if($cohorts->isEmpty())
        <div class="text-muted">Todavía no hay cohortes creadas para este curso.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th style="width:70px;">ID</th>
                <th>Nombre</th>
                <th style="width:120px;">Inicio</th>
                <th style="width:120px;">Fin</th>
                <th style="width:120px;">Total</th>
                <th style="width:90px;">Cuotas</th>
                <th style="width:100px;">Vence día</th>
                <th style="width:120px;">Inscripción</th>
                <th style="width:100px;">Público</th>
                <th style="width:110px;">Cupo</th>
                <th style="width:180px;"></th>
              </tr>
            </thead>

            <tbody>
              @foreach($cohorts as $cohort)
                @php
                  $total = $cohort->price_total ?? $cohort->price_ars ?? null;
                  $cupo  = $cohort->max_seats ?? $cohort->capacity ?? null;
                @endphp

                <tr>
                  <td>#{{ $cohort->id }}</td>

                  <td>
                    <div class="fw-semibold">{{ $cohort->name ?? '-' }}</div>
                    @if(!empty($cohort->label))
                      <div class="text-muted small">{{ $cohort->label }}</div>
                    @endif
                  </td>

                  <td>{{ $cohort->start_date ?? '-' }}</td>
                  <td>{{ $cohort->end_date ?? '-' }}</td>

                  <td>{{ $total !== null ? number_format((float)$total, 0, ',', '.') : '-' }}</td>
                  <td>{{ $cohort->installments_count ?? '-' }}</td>
                  <td>{{ $cohort->installment_due_day ?? '-' }}</td>

                  <td>
                    <span class="badge {{ !empty($cohort->enrollment_open) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                      {{ !empty($cohort->enrollment_open) ? 'Abierta' : 'Cerrada' }}
                    </span>
                  </td>

                  <td>
                    <span class="badge {{ !empty($cohort->is_public) ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}">
                      {{ !empty($cohort->is_public) ? 'Sí' : 'No' }}
                    </span>
                  </td>

                  <td>{{ $cupo ?? '-' }}</td>

                  <td class="text-end">
                    <a class="btn btn-sm btn-ghost"
                       href="{{ route('admin.academic.cohorts.edit', [$course, $cohort]) }}">
                      Editar
                    </a>

                   
                    Activar cuando agreguemos la ruta destroy:
                    <form method="POST" action="{{ route('admin.academic.cohorts.destroy', [$course, $cohort]) }}"
                          style="display:inline"
                          onsubmit="return confirm('¿Eliminar esta cohorte? Esta acción no se puede deshacer.');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                    
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif

    </div>
  </div>
@endsection
