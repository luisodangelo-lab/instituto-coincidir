@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-bold mb-2">Inscripción</h1>
  <p class="mb-4"><b>{{ $course->title }}</b></p>

  @if(session('ok')) <div class="p-3 mb-3 bg-green-100">{{ session('ok') }}</div> @endif
  @if($errors->any())
    <div class="p-3 mb-3 bg-red-100">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('public.enroll.store', $course->id) }}">
    @csrf
    <label>Nombre y apellido</label>
    <input name="name" value="{{ old('name') }}" required>

    <label>DNI</label>
    <input name="dni" value="{{ old('dni') }}" required>

    <label>Email</label>
    <input name="email" value="{{ old('email') }}" required>

    <label>WhatsApp</label>
    <input name="phone_whatsapp" value="{{ old('phone_whatsapp') }}" required>

    <button class="mt-4 px-4 py-2 bg-black text-white rounded">Preinscribirme</button>
  </form>
</div>
@endsection
