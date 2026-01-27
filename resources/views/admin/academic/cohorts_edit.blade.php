@extends('layouts.app')
@section('title','Editar cohorte')
@section('page_title','Editar cohorte')
@section('page_hint','Modificá datos y condiciones de inscripción.')

@section('content')
  <div style="display:flex;justify-content:space-between;gap:10px;margin-bottom:12px;">
    <a class="btn btn-ghost" href="{{ route('admin.academic.cohorts.index', $course) }}">Volver a cohortes</a>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <form method="POST" action="{{ route('admin.academic.cohorts.update', [$course, $cohort]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input name="name" class="form-control" value="{{ old('name', $cohort->name) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Etiqueta (opcional)</label>
          <input name="label" class="form-control" value="{{ old('label', $cohort->label) }}">
        </div>

        <div class="row g-2">
          <div class="col-md-6 mb-3">
            <label class="form-label">Inicio</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $cohort->start_date) }}">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Fin</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $cohort->end_date) }}">
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-6 mb-3">
            <label class="form-label">Precio total</label>
            <input type="number" step="1" name="price_total" class="form-control"
                   value="{{ old('price_total', $cohort->price_total) }}">
            
                 </div>

        <div class="row g-2">
          <div class="col-md-4 mb-3">
            <label class="form-label">Cantidad de cuotas</label>
            <input type="number" name="installments_count" class="form-control"
       min="1" max="12">

                  
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Día de vencimiento</label>
          <input type="number" name="installment_due_day" class="form-control"
       min="1" max="28">

                   
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Capacidad</label>
            <input type="number" name="max_seats" class="form-control"
       value="{{ old('max_seats', $cohort->max_seats) }}">

          </div>
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="enrollment_open" value="1"
                 @checked(old('enrollment_open', $cohort->enrollment_open))>
          <label class="form-check-label">Inscripción abierta</label>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_public" value="1"
                 @checked(old('is_public', $cohort->is_public))>
          <label class="form-check-label">Visible al público</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Notas</label>
          <textarea name="notes" class="form-control w-100" rows="6">{{ old('notes', $cohort->notes) }}</textarea>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;">
          <button class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <div class="fw-semibold mb-2">{{ $course->code }} — {{ $course->title }}</div>
          @if(!empty($course->cover_path))
            <img src="{{ asset('storage/'.$course->cover_path) }}" class="img-fluid rounded" alt="Portada curso">
          @else
            <div class="text-muted small">Sin imagen cargada.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
