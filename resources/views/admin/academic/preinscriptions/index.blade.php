@extends('layouts.app')
@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 m-0">Preinscripciones</h1>
    <a class="btn btn-outline-secondary" href="{{ route('admin.academic.courses.index') }}">Académico</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped align-middle m-0">
        <thead>
          <tr>
            <th>Estado</th>
            <th>Alumno</th>
            <th>Curso</th>
            <th>Cohorte</th>
            <th>Comprobante</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach($rows as $enr)
          @php $course = $enr->cohort?->course; @endphp
          <tr>
            <td>
              <span class="badge {{ $enr->status==='pendiente_pago'?'text-bg-warning':'text-bg-secondary' }}">
                {{ $enr->status }}
              </span>
            </td>
            <td>
              <div class="fw-semibold">{{ $enr->user->name ?? '-' }}</div>
              <div class="text-muted small">DNI: {{ $enr->user->dni ?? '-' }} · {{ $enr->user->email ?? '' }}</div>
            </td>
            <td>{{ $course->title ?? '-' }}</td>
            <td>{{ $enr->cohort->name ?? $enr->cohort_id }}</td>
            <td>
              @if($enr->public_token)
                <a class="btn btn-sm btn-outline-primary"
                   href="{{ route('public.receipt.show', ['token'=>$enr->public_token]) }}"
                   target="_blank">
                   Ver/Cargar
                </a>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td class="text-end">
              <form method="POST" action="{{ route('admin.academic.enrollments.mark_inscripto', $enr) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success">Marcar inscripto</button>
              </form>

              <form method="POST" action="{{ route('admin.academic.enrollments.installments.generate', $enr) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-primary">Generar cuotas</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $rows->links() }}
  </div>
</div>
@endsection

