@extends('layouts.app')
@section('title','Crear curso')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Crear curso</h1>
    <a href="{{ route('admin.academic.courses.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
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

      <form method="POST" action="{{ route('admin.academic.courses.store') }}">
        @csrf

        <div class="row g-3">

          <div class="col-md-4">
            <label class="form-label">Código</label>
            <input name="code" class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code') }}" placeholder="CUR-EXCEL-01">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-8">
            <label class="form-label">Título</label>
            <input name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" placeholder="Excel Inicial">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select @error('type') is-invalid @enderror">
              @php $type = old('type', 'curso'); @endphp
              <option value="curso" {{ $type==='curso'?'selected':'' }}>Curso</option>
              <option value="tecnicatura" {{ $type==='tecnicatura'?'selected':'' }}>Tecnicatura</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Modalidad</label>
            <input name="modality" class="form-control @error('modality') is-invalid @enderror"
                   value="{{ old('modality', 'online') }}" placeholder="online">
            @error('modality') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" value="1"
                     id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Activo</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Breve descripción...">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Meses</label>
            <input type="number" name="months" class="form-control @error('months') is-invalid @enderror"
                   value="{{ old('months') }}" min="1" max="60">
            @error('months') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Semanas</label>
            <input type="number" name="duration_weeks" class="form-control @error('duration_weeks') is-invalid @enderror"
                   value="{{ old('duration_weeks') }}" min="1" max="260">
            @error('duration_weeks') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Horas</label>
            <input type="number" name="hours_total" class="form-control @error('hours_total') is-invalid @enderror"
                   value="{{ old('hours_total') }}" min="1" max="5000">
            @error('hours_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-12"><hr></div>

          <div class="col-md-6">
            <label class="form-label">Nº Expediente</label>
            <input name="expediente_number" class="form-control @error('expediente_number') is-invalid @enderror"
                   value="{{ old('expediente_number') }}">
            @error('expediente_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Nº Resolución</label>
            <input name="resolution_number" class="form-control @error('resolution_number') is-invalid @enderror"
                   value="{{ old('resolution_number') }}">
            @error('resolution_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha de presentación</label>
            <input type="date" name="presentation_date" class="form-control @error('presentation_date') is-invalid @enderror"
                   value="{{ old('presentation_date') }}">
            @error('presentation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="ministry_approved" value="1"
                     id="ministry_approved" {{ old('ministry_approved') ? 'checked' : '' }}>
              <label class="form-check-label" for="ministry_approved">Aprobado por Ministerio</label>
            </div>
          </div>

        </div>

        <div class="mt-3">
          <button class="btn btn-primary">Guardar</button>
        </div>

      </form>

    </div>
  </div>
</div>
@endsection
