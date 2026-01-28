@extends('layouts.app')
@section('title','Cohortes')
@section('page_title','Cohortes')
@section('page_hint','Listado general de cohortes.')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Curso</th>
              <th>Cohorte</th>
              <th>Inicio</th>
              <th>Fin</th>
              <th>Total</th>
              <th>Cuotas</th>
              <th>Abierta</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($cohorts as $cohort)
              @php $course = $cohort->course; @endphp
            <tr>
            <td>#{{ $cohort->id }}</td>

 <td>
  @if($course)
    <div class="d-flex gap-2 align-items-center">
      @if(!empty($course->cover_path))
        <img src="{{ asset('storage/'.$course->cover_path) }}"
             style="width:48px;height:48px;object-fit:cover;border-radius:8px"
             alt="Curso">
      @else
        <div style="width:48px;height:48px;border-radius:8px;background:#e5e7eb;"></div>
      @endif

      <div>
        <div class="fw-semibold">{{ $course->title }}</div>
        <div class="text-muted small">{{ $course->code }}</div>
      </div>
    </div>
  @else
    <span class="text-muted">—</span>
  @endif
</td>


                <td>{{ $cohort->name }}</td>
                <td>{{ $cohort->start_date ?? '-' }}</td>
                <td>{{ $cohort->end_date ?? '-' }}</td>
                <td>{{ $cohort->price_total ?? '-' }}</td>
                <td>{{ $cohort->installments_count ?? '-' }}</td>
                <td>{{ $cohort->enrollment_open ? 'Sí' : 'No' }}</td>

                <td class="text-end">
                  @if($course)
                    <a class="btn btn-sm btn-ghost"
                      href="{{ route('admin.academic.cohorts.index', $course) }}">
                      Ver del curso
                    </a>
                    <a class="btn btn-sm btn-ghost"
                      href="{{ route('admin.academic.cohorts.edit', [$course, $cohort]) }}">
                      Editar
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{ $cohorts->links() }}
    </div>
  </div>
@endsection
