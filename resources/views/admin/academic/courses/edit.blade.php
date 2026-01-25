@extends('layouts.app')
@section('title','Editar curso')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Editar curso</h1>
    <a href="{{ route('admin.academic.courses.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Revisá los campos marcados</div>
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

 <form method="POST" 
        action="{{ route('admin.academic.courses.update', $course) }}" 
        enctype="multipart/form-data">
        
    @csrf
    @method('PUT')

    @include('admin.academic.courses._form', ['course' => $course])

    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Guardar cambios</button>
      <a class="btn btn-outline-secondary" href="{{ route('admin.academic.courses.index') }}">Cancelar</a>
    </div>
  </form>
</div>
@endsection
