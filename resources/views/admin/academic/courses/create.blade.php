@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Nuevo curso</h1>
    <a class="btn btn-outline-secondary" href="{{ route('admin.academic.courses.index') }}">Volver</a>
  </div>

  <form method="POST" action="{{ route('admin.academic.courses.store') }}" enctype="multipart/form-data">
    @csrf

    @include('admin.academic.courses._form', ['course' => $course])

    <div class="mt-3">
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
@endsection
