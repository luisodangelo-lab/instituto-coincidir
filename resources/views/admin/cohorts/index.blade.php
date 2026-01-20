@extends('layouts.app')
@section('title','Cohortes')

@section('content')
  <h2>Cohortes · {{ $course->title }}</h2>

  @php $r = auth()->user()->role ?? 'alumno';
       $canEdit = in_array($r, ['admin','staff_l1','administrativo']);
  @endphp

  @if($canEdit)
    <p><a href="{{ route('admin.cohorts.create', $course) }}">+ Crear cohorte</a></p>
  @endif

  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>Nombre</th><th>Inicio</th><th>Fin</th><th>Total</th><th>Cuotas</th><th>Activo</th><th></th>
    </tr>
    @foreach($cohorts as $c)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $c->name }}</td>
        <td>{{ $c->start_date }}</td>
        <td>{{ $c->end_date }}</td>
        <td>${{ number_format($c->price_total,2,',','.') }}</td>
        <td>{{ $c->installments_count }}</td>
        <td>{{ $c->is_active ? 'Sí' : 'No' }}</td>
        <td>
          @if($canEdit)
            <a href="{{ route('admin.cohorts.edit', [$course, $c]) }}">Editar</a>
          @endif
        </td>
      </tr>
    @endforeach
  </table>

  <div style="margin-top:12px;">{{ $cohorts->links() }}</div>
@endsection
