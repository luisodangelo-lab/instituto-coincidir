@extends('layouts.app')
@section('title','Cursos')

@section('content')
  <h2>Admin · Cursos</h2>

  @php $r = auth()->user()->role ?? 'alumno';
       $canEdit = in_array($r, ['admin','staff_l1','administrativo']);
  @endphp

  @if($canEdit)
    <p><a href="{{ route('admin.courses.create') }}">+ Crear curso</a></p>
  @endif

  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>Código</th><th>Título</th><th>Tipo</th><th>Activo</th><th></th>
    </tr>
    @foreach($courses as $c)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $c->code }}</td>
        <td>{{ $c->title }}</td>
        <td>{{ $c->type }}</td>
        <td>{{ $c->is_active ? 'Sí' : 'No' }}</td>
        <td>
          <a href="{{ route('admin.cohorts.index', $c) }}">Cohortes</a>
          @if($canEdit)
            · <a href="{{ route('admin.courses.edit', $c) }}">Editar</a>
          @endif
        </td>
      </tr>
    @endforeach
  </table>

  <div style="margin-top:12px;">{{ $courses->links() }}</div>
@endsection
