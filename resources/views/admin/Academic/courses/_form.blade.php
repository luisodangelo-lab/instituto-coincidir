@php
  $isEdit = isset($course) && $course;
@endphp

<div class="row g-3">

  {{-- Básico --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-light fw-semibold">Datos básicos</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Código</label>
            <input name="code" class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code', $course->code ?? '') }}"
                   placeholder="CUR-EXCEL-01 / TEC-ADM-2026">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Único. Recomendado en mayúsculas.</div>
          </div>

          <div class="col-md-8">
            <label class="form-label">Título</label>
            <input name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $course->title ?? '') }}"
                   placeholder="Excel Inicial">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select @error('type') is-invalid @enderror">
              @php $type = old('type', $course->type ?? 'curso'); @endphp
              <option value="curso" {{ $type==='curso'?'selected':'' }}>Curso</option>
              <option value="tecnicatura" {{ $type==='tecnicatura'?'selected':'' }}>Tecnicatura</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Modalidad</label>
            <input name="modality" class="form-control @error('modality') is-invalid @enderror"
                   value="{{ old('modality', $course->modality ?? 'online') }}"
                   placeholder="online / híbrida / presencial">
            @error('modality') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 d-flex align-items-end">
            @php $active = old('is_active', $course->is_active ?? true); @endphp
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" value="1"
                     id="is_active" {{ $active ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Activo (visible)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="4"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Breve descripción del curso...">{{ old('description', $course->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- Duración / carga horaria --}}
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light fw-semibold">Duración y carga horaria</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Meses</label>
            <input type="number" name="months" min="1" max="60"
                   class="form-control @error('months') is-invalid @enderror"
                   value="{{ old('months', $course->months ?? '') }}">
            @error('months') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Semanas</label>
            <input type="number" name="duration_weeks" min="1" max="260"
                   class="form-control @error('duration_weeks') is-invalid @enderror"
                   value="{{ old('duration_weeks', $course->duration_weeks ?? '') }}">
            @error('duration_weeks') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Horas</label>
            <input type="number" name="hours_total" min="1" max="5000"
                   class="form-control @error('hours_total') is-invalid @enderror"
                   value="{{ old('hours_total', $course->hours_total ?? '') }}">
            @error('hours_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 text-muted small">
            Tip: para cursos cortos suele bastar Semanas + Horas. Para tecnicaturas, Meses/Semanas ayudan a ordenar cohortes.
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Datos Ministerio --}}
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light fw-semibold">Ministerio / Expediente</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nº Expediente</label>
            <input name="expediente_number" class="form-control @error('expediente_number') is-invalid @enderror"
                   value="{{ old('expediente_number', $course->expediente_number ?? '') }}"
                   placeholder="Expte. ...">
            @error('expediente_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Nº Resolución</label>
            <input name="resolution_number" class="form-control @error('resolution_number') is-invalid @enderror"
                   value="{{ old('resolution_number', $course->resolution_number ?? '') }}"
                   placeholder="Res. ...">
            @error('resolution_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha de presentación</label>
            <input type="date" name="presentation_date"
                   class="form-control @error('presentation_date') is-invalid @enderror"
                   value="{{ old('presentation_date', optional($course->presentation_date ?? null)->format('Y-m-d')) }}">
            @error('presentation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6 d-flex align-items-end">
            @php $approved = old('ministry_approved', $course->ministry_approved ?? false); @endphp
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="ministry_approved" value="1"
                     id="ministry_approved" {{ $approved ? 'checked' : '' }}>
              <label class="form-check-label" for="ministry_approved">Aprobado por Ministerio</label>
            </div>
          </div>

          <div class="col-12 text-muted small">
            Si no está aprobado todavía, dejá desmarcado “Aprobado” y cargá solo el expediente/presentación.
          </div>

        </div>
      </div>
    </div>
  </div>

</div>
