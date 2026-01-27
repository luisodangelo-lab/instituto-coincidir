@extends('layouts.app')
@section('title','Cohortes')
@section('page_title','Cohortes del curso')
@section('page_hint','Gestioná las cohortes (crear / editar).')

@section('content')
  <div style="display:flex;justify-content:space-between;gap:10px;margin-bottom:12px;">
    <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Volver</a>

    <div style="display:flex;gap:10px;">
      <a class="btn btn-primary" href="{{ route('admin.academic.cohorts.create', $course) }}">Nueva cohorte</a>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="fw-semibold mb-2">{{ $course->code }} — {{ $course->title }}</div>

      @if($cohorts->isEmpty())
        <div class="text-muted">Todavía no hay cohortes creadas para este curso.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Cuotas</th>
                <th>Total</th>
                <th>Activa</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($cohorts as $cohort)
                <tr>
                  <td>{{ $cohort->id }}</td>
                  <td>{{ $cohort->name ?? '-' }}</td>
                  <td>{{ $cohort->start_date ?? '-' }}</td>
                  <td>{{ $cohort->end_date ?? '-' }}</td>
                  <td>{{ $cohort->installments_count ?? '-' }}</td>
                  <td>{{ $cohort->total_amount ?? '-' }}</td>
                  <td>{{ isset($cohort->is_active) ? ($cohort->is_active ? 'Sí' : 'No') : '-' }}</td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-ghost"
                       href="{{ route('admin.academic.cohorts.edit', [$course, $cohort]) }}">
                      Editar
                    </a>
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
