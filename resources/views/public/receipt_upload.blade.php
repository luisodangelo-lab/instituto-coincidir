@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-bold mb-2">Subir comprobante</h1>
  <p class="mb-4">
    Curso: <b>{{ $enr->cohort->course->title }}</b><br>
    Alumno: {{ $enr->user->name }} (DNI {{ $enr->user->dni }})
  </p>

  @if(session('ok')) <div class="p-3 mb-3 bg-green-100">{{ session('ok') }}</div> @endif
  @if($errors->any())
    <div class="p-3 mb-3 bg-red-100">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('public.receipt.store', $enr->public_token) }}" enctype="multipart/form-data">
    @csrf
    <label>Comprobante (PDF/JPG/PNG)</label>
    <input type="file" name="receipt" required>

    <button class="mt-4 px-4 py-2 bg-black text-white rounded">Subir</button>
  </form>

  @if($enr->receipt_path)
    <p class="mt-4 text-sm opacity-75">Ya hay un comprobante cargado. Si subís otro, se reemplazará.</p>
  @endif
</div>
@endsection
