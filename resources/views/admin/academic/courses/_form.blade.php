{{-- resources/views/admin/academic/courses/_form.blade.php --}}
<div class="row g-3">

  {{-- Código / Tipo --}}
  <div class="col-md-3">
    <label class="form-label">Código</label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
           value="{{ old('code', $course->code ?? '') }}" placeholder="A1-CFRPRE" required>
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Tipo</label>
    <select name="type" class="form-select @error('type') is-invalid @enderror">
      @php $t = old('type', $course->type ?? 'curso'); @endphp
      <option value="curso" @selected($t==='curso')>Curso</option>
      <option value="tecnicatura" @selected($t==='tecnicatura')>Tecnicatura</option>
      <option value="posgrado" @selected($t==='posgrado')>Posgrado</option>
      <option value="oficio" @selected($t==='oficio')>Oficio</option>
      <option value="docente" @selected($t==='docente')>Capacitación docente</option>
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Título --}}
  <div class="col-md-6">
    <label class="form-label">Título</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $course->title ?? '') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Descripción --}}
  <div class="col-12">
    <label class="form-label">Descripción</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
              placeholder="Resumen corto para la oferta académica">{{ old('description', $course->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Ejes / Contenidos --}}
  <div class="col-md-6">
    <label class="form-label">Ejes / Núcleos temáticos</label>
    <textarea name="axes" rows="5" class="form-control @error('axes') is-invalid @enderror"
              placeholder="• Eje 1: ...&#10;• Eje 2: ...">{{ old('axes', $course->axes ?? '') }}</textarea>
    @error('axes') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Contenidos</label>
    <textarea name="contents" rows="5" class="form-control @error('contents') is-invalid @enderror"
              placeholder="Listado o desarrollo de contenidos">{{ old('contents', $course->contents ?? '') }}</textarea>
    @error('contents') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Duración / Precio --}}
  <div class="col-md-3">
    <label class="form-label">Horas</label>
    <input type="number" name="duration_hours" min="0"
           class="form-control @error('duration_hours') is-invalid @enderror"
           value="{{ old('duration_hours', $course->duration_hours ?? '') }}">
    @error('duration_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Precio (ARS)</label>
    <input type="number" name="price_ars" min="0"
           class="form-control @error('price_ars') is-invalid @enderror"
           value="{{ old('price_ars', $course->price_ars ?? '') }}">
    @error('price_ars') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Aprobación / Expte / Disposición --}}
  <div class="col-md-3">
    <label class="form-label">Expediente</label>
    <input type="text" name="expediente" class="form-control @error('expediente') is-invalid @enderror"
           value="{{ old('expediente', $course->expediente ?? '') }}" placeholder="Expte. 1234/25">
    @error('expediente') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">N° Disposición / Resolución</label>
    <input type="text" name="resolution_number" class="form-control @error('resolution_number') is-invalid @enderror"
           value="{{ old('resolution_number', $course->resolution_number ?? '') }}" placeholder="Disp. N° ... / Res. N° ...">
    @error('resolution_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Autoridad</label>
    <input type="text" name="authority" class="form-control @error('authority') is-invalid @enderror"
           value="{{ old('authority', $course->authority ?? '') }}" placeholder="DGEP / Ministerio / etc.">
    @error('authority') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Fecha aprobación</label>
    <input type="date" name="approval_date" class="form-control @error('approval_date') is-invalid @enderror"
           value="{{ old('approval_date', $course->approval_date ?? '') }}">
    @error('approval_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Imagen / Brochure --}}
  <div class="col-md-6">
    <label class="form-label">Imagen (portada)</label>
    <input type="file" name="cover_image" accept="image/*"
           class="form-control @error('cover_image') is-invalid @enderror">
    @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if(!empty($course?->cover_image_path))
      <div class="mt-2 d-flex align-items-center gap-3">
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->cover_image_path) }}"
             class="img-thumbnail" style="max-width:140px" alt="Portada">
        <div class="small text-muted">
          Ya hay una imagen cargada. Si subís otra, se reemplaza.
        </div>
      </div>
    @endif
  </div>

  <div class="col-md-6">
    <label class="form-label">PDF / Brochure (opcional)</label>
    <input type="file" name="brochure_pdf" accept="application/pdf"
           class="form-control @error('brochure_pdf') is-invalid @enderror">
    @error('brochure_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if(!empty($course?->brochure_pdf_path))
      <div class="mt-2">
        <a target="_blank"
           href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->brochure_pdf_path) }}">
          Ver PDF actual
        </a>
        <span class="small text-muted"> (si subís otro, se reemplaza)</span>
      </div>
    @endif
  </div>

  {{-- Checks --}}
  <div class="col-12">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
             @checked(old('is_active', $course->is_active ?? 1))>
      <label class="form-check-label" for="is_active">Activo</label>
    </div>

    <div class="form-check form-switch mt-1">
      <input class="form-check-input" type="checkbox" role="switch" id="ministry_approved" name="ministry_approved" value="1"
             @checked(old('ministry_approved', $course->ministry_approved ?? 0))>
      <label class="form-check-label" for="ministry_approved">Aprobado por autoridad educativa</label>
    </div>
  </div>

</div>
