@php
  $isEdit = isset($course) && $course;
@endphp

<style>
  .ic-box{border:1px solid var(--border);border-radius:16px;background:#fff;padding:14px;margin-bottom:12px}
  .ic-box h3{margin:0 0 10px;font-size:16px}
  .ic-grid{display:grid;gap:12px}
  @media(min-width:900px){ .ic-grid-2{grid-template-columns:1fr 1fr} .ic-grid-3{grid-template-columns:1fr 1fr 1fr} }
  .ic-field label{display:block;font-weight:800;margin:0 0 6px}
  .ic-help{font-size:12px;color:#64748b;margin-top:6px}
  textarea{min-height:90px}
</style>

<div class="ic-box">
  <h3>Datos básicos</h3>
  <div class="ic-grid ic-grid-2">
    <div class="ic-field">
      <label>Código</label>
      <input name="code" value="{{ old('code', $course->code ?? '') }}" placeholder="CUR-EXCEL-01 / TEC-ADM-2026">
      <div class="ic-help">Único. Recomendado en mayúsculas.</div>
    </div>

    <div class="ic-field">
      <label>Título</label>
      <input name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="Excel Inicial">
    </div>

    <div class="ic-field">
      <label>Tipo</label>
      @php $type = old('type', $course->type ?? 'curso'); @endphp
      <select name="type">
        <option value="curso" {{ $type==='curso'?'selected':'' }}>Curso</option>
        <option value="tecnicatura" {{ $type==='tecnicatura'?'selected':'' }}>Tecnicatura</option>
      </select>
    </div>

    <div class="ic-field">
      <label>Modalidad</label>
      <input name="modality" value="{{ old('modality', $course->modality ?? 'online') }}" placeholder="online / híbrida / presencial">
    </div>

    <div class="ic-field" style="display:flex;align-items:flex-end;gap:10px">
      @php $active = old('is_active', $course->is_active ?? true); @endphp
      <label style="margin:0;font-weight:800;">
        <input type="checkbox" name="is_active" value="1" {{ $active ? 'checked' : '' }}>
        Activo (visible)
      </label>
    </div>
  </div>

  <div class="ic-field" style="margin-top:12px">
    <label>Descripción breve</label>
    <textarea name="description" rows="4" placeholder="Breve descripción del curso...">{{ old('description', $course->description ?? '') }}</textarea>
  </div>
</div>

<div class="ic-box">
  <h3>Carátula y normativa</h3>
  <div class="ic-grid ic-grid-2">
    <div class="ic-field">
      <label>Carátula (imagen)</label>
      <input type="file" name="cover_image" accept="image/*">
      @if($isEdit && !empty($course->cover_path))
        <div class="ic-help">
          Actual: <a href="{{ asset('storage/'.$course->cover_path) }}" target="_blank" rel="noopener">ver imagen</a>
        </div>
      @endif
      <div class="ic-help">JPG/PNG/WebP. Recomendado 1200×800.</div>
    </div>

    <div class="ic-field">
      <label>Normativa (PDF)</label>
      <input type="file" name="normative_pdf" accept="application/pdf">
      @if($isEdit && !empty($course->normative_pdf_path))
        <div class="ic-help">
          Actual: <a href="{{ asset('storage/'.$course->normative_pdf_path) }}" target="_blank" rel="noopener">ver PDF</a>
        </div>
      @endif
      <div class="ic-help">PDF con resolución/disposición o documento de referencia.</div>
    </div>
  </div>

  <div class="ic-grid ic-grid-3" style="margin-top:12px">
    <div class="ic-field">
      <label>Nº Expediente</label>
      <input name="expediente_number" value="{{ old('expediente_number', $course->expediente_number ?? '') }}" placeholder="Expte. ...">
    </div>
    <div class="ic-field">
      <label>Nº Resolución / Disposición</label>
      <input name="resolution_number" value="{{ old('resolution_number', $course->resolution_number ?? '') }}" placeholder="Res./Disp. ...">
    </div>
    <div class="ic-field">
      <label>Fecha de presentación</label>
      <input type="date" name="presentation_date" value="{{ old('presentation_date', isset($course->presentation_date) ? \Illuminate\Support\Carbon::parse($course->presentation_date)->format('Y-m-d') : '') }}">
    </div>
  </div>

  <div class="ic-field" style="margin-top:10px">
    @php $approved = old('ministry_approved', $course->ministry_approved ?? false); @endphp
    <label style="margin:0;font-weight:800;">
      <input type="checkbox" name="ministry_approved" value="1" {{ $approved ? 'checked' : '' }}>
      Aprobado por Ministerio
    </label>
  </div>
</div>

<div class="ic-box">
  <h3>Ejes y contenidos</h3>
  <div class="ic-grid ic-grid-2">
    <div class="ic-field">
      <label>Ejes / núcleos temáticos</label>
      <textarea name="axes" rows="6" placeholder="Ej: Eje 1: ...&#10;Eje 2: ...">{{ old('axes', $course->axes ?? '') }}</textarea>
      <div class="ic-help">Pegalos en formato texto; después lo maquetamos lindo en la ficha pública.</div>
    </div>

    <div class="ic-field">
      <label>Contenidos</label>
      <textarea name="contents" rows="6" placeholder="Lista de contenidos...">{{ old('contents', $course->contents ?? '') }}</textarea>
    </div>
  </div>
</div>
