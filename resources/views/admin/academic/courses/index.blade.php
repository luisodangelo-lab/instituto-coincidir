@extends('layouts.app')

@section('content')
  <h1 style="margin-bottom:12px;">Cursos</h1>

  @if(session('ok'))
    <div style="padding:10px;border:1px solid #b6f2c2;background:#e9fff0;margin-bottom:12px;">
      {{ session('ok') }}
    </div>
  @endif

  <div style="margin-bottom:12px;">
    <a href="{{ route('admin.academic.courses.create') }}">+ Crear curso</a>
    <a href="{{ route('admin.academic.enrollments.create') }}">+ Nueva matrícula</a>

  </div>

  <table border="1" cellpadding="8" cellspacing="0" style="width:100%;border-collapse:collapse;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Título</th>
        <th>Activo</th>
        <th>Cohortes</th>
      </tr>
    </thead>
    <tbody>
      @forelse($courses as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->code }}</td>
          <td>{{ $c->title }}</td>
          <td>{{ $c->is_active ? 'Sí' : 'No' }}</td>
          <td>
            <a href="{{ route('admin.academic.cohorts.create', $c) }}">Crear cohorte</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="5">No hay cursos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $courses->links() }}
  </div>
@endsection
