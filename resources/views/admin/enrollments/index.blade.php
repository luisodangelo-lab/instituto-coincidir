@extends('layouts.app')
@section('title','Matrículas')

@section('content')
  <h2>Admin · Matrículas</h2>

  @php $r = auth()->user()->role ?? 'alumno';
       $canEdit = in_array($r, ['admin','staff_l1','administrativo']);
  @endphp

  @if($canEdit)
    <p><a href="{{ route('admin.enrollments.create') }}">+ Matricular por DNI</a></p>
  @endif

  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>ID</th><th>Alumno</th><th>Curso</th><th>Cohorte</th><th>Estado</th><th></th>
    </tr>
    @foreach($enrollments as $e)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $e->id }}</td>
        <td>{{ $e->user->name }} ({{ $e->user->dni }})</td>
        <td>{{ $e->cohort->course->title }}</td>
        <td>{{ $e->cohort->name }}</td>
        <td>{{ $e->status }}</td>
        <td><a href="{{ route('admin.enrollments.show', $e) }}">ver</a></td>
      </tr>
    @endforeach
  </table>

  <div style="margin-top:12px;">{{ $enrollments->links() }}</div>
@endsection
