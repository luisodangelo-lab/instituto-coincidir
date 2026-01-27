<div class="row g-3">

  {{-- Código / Título --}}
  <div class="col-md-4">
    <label class="form-label">Código</label>
    <input name="code" class="form-control @error('code') is-invalid @enderror"
           value="{{ old('code', $course->code ?? '') }}" placeholder="A1-XXXX">
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-8">
    <label class="form-label">Título</label>
    <input name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $course->title ?? '') }}">
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Tipo / Modalidad --}}
  <div class="col-md-4">
    <label class="form-label">Tipo</label>
    @php $type = old('type', $course->type ?? 'curso'); @endphp
    <select name="type" class="form-select @error('type') is-invalid @enderror">
      <option value="curso" {{ $type==='curso'?'selected':'' }}>Curso</option>
      <option value="tecnicatura" {{ $type==='tecnicatura'?'selected':'' }}>Tecnicatura</option>
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Modalidad</label>
    <input name="modality" class="form-control @error('modality') is-invalid @enderror"
           value="{{ old('modality', $course->modality ?? 'online') }}">
    @error('modality') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Activo + Aprobado (dropdowns juntos) --}}
  <div class="col-md-2">
    <label class="form-label">Activo (visible)</label>
    @php $act = (string)old('is_active', isset($course) ? (int)($course->is_active ?? 1) : 1); @endphp
    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
      <option value="1" {{ $act==='1'?'selected':'' }}>Sí</option>
      <option value="0" {{ $act==='0'?'selected':'' }}>No</option>
    </select>
    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">Aprobado por Ministerio</label>
    @php $ap = (string)old('ministry_approved', isset($course) ? (int)($course->ministry_approved ?? 0) : 0); @endphp
    <select name="ministry_approved" class="form-select @error('ministry_approved') is-invalid @enderror">
      <option value="1" {{ $ap==='1'?'selected':'' }}>Sí</option>
      <option value="0" {{ $ap==='0'?'selected':'' }}>No</option>
    </select>
    @error('ministry_approved') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Descripción + Ejes (lado a lado y anchos) --}}
  <div class="col-lg-6">
    <label class="form-label">Descripción breve</label>
    <textarea name="description" rows="7"
              class="form-control @error('description') is-invalid @enderror"
              style="max-width:none">{{ old('description', $course->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-lg-6">
    <label class="form-label">Ejes / núcleos temáticos</label>
    <textarea name="axes" rows="7"
              class="form-control @error('axes') is-invalid @enderror"
              style="max-width:none">{{ old('axes', $course->axes ?? '') }}</textarea>
    @error('axes') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Contenidos (debajo, ancho completo) --}}
  <div class="col-12">
    <label class="form-label">Contenidos</label>
    <textarea name="contents" rows="8"
              class="form-control @error('contents') is-invalid @enderror"
              style="max-width:none">{{ old('contents', $course->contents ?? '') }}</textarea>
    @error('contents') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12"><hr class="my-2"></div>

  {{-- Carátula + Normativa (con vista previa) --}}
  <div class="col-md-6">
    <label class="form-label">Carátula (imagen)</label>
    <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror" accept="image/*">
    @error('cover') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if(!empty($course->cover_path))
      <div class="mt-2">
        <img src="{{ asset('storage/'.$course->cover_path) }}"
             style="width:240px;max-width:100%;height:auto;border-radius:14px;border:1px solid #e5e7eb;">
      </div>
    @endif
  </div>

  <div class="col-md-6">
    <label class="form-label">Normativa (PDF)</label>
    <input type="file" name="normative_pdf" class="form-control @error('normative_pdf') is-invalid @enderror" accept="application/pdf">
    @error('normative_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if(!empty($course->normative_pdf_path))
      <div class="mt-2">
        <a href="{{ asset('storage/'.$course->normative_pdf_path) }}" target="_blank" rel="noopener">
          Ver PDF actual
        </a>
      </div>
    @endif
  </div>

  {{-- Normativa: números --}}
  <div class="col-md-4">
    <label class="form-label">N° Expediente</label>
    <input name="expediente_number" class="form-control @error('expediente_number') is-invalid @enderror"
           value="{{ old('expediente_number', $course->expediente_number ?? '') }}">
    @error('expediente_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">N° Resolución</label>
    <input name="resolution_number" class="form-control @error('resolution_number') is-invalid @enderror"
           value="{{ old('resolution_number', $course->resolution_number ?? '') }}">
    @error('resolution_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">N° Disposición</label>
    <input name="disposition_number" class="form-control @error('disposition_number') is-invalid @enderror"
           value="{{ old('disposition_number', $course->disposition_number ?? '') }}">
    @error('disposition_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Fecha de presentación</label>
    <input type="date" name="presentation_date" class="form-control @error('presentation_date') is-invalid @enderror"
           value="{{ old('presentation_date', $course->presentation_date ?? '') }}">
    @error('presentation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

</div>
