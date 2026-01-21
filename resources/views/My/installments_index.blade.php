@extends('layouts.app')
@section('title','Mis cuotas')

@section('content')
  <style>
    .muted { opacity:.75; }
    .table-wrap { overflow:auto; border-radius:16px; border:1px solid rgba(148,163,184,.35); }
    .tbl { width:100%; border-collapse: collapse; min-width: 720px; }
    .tbl th, .tbl td { padding:10px 10px; border-top:1px solid rgba(148,163,184,.22); vertical-align: middle; }
    .tbl th { text-align:left; font-weight:700; border-top:0; background: rgba(148,163,184,.10); }
    .row-paid { background: rgba(16,185,129,.08); }
    .dot { width:10px; height:10px; border-radius:999px; display:inline-block; }
    .dot-paid { background: rgb(16,185,129); }
    .dot-partial { background: rgb(245,158,11); }
    .dot-unpaid { background: rgb(148,163,184); }

    .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; }
    .badge-paid { background: rgba(16,185,129,.15); color: rgb(6,95,70); }
    .badge-partial { background: rgba(245,158,11,.18); color: rgb(146,64,14); }
    .badge-unpaid { background: rgba(148,163,184,.25); color: rgb(51,65,85); }

    .btn-pay { display:inline-block; padding:7px 12px; border-radius:12px; border:1px solid rgba(17,24,39,.25); background: rgba(17,24,39,.95); color:#fff; text-decoration:none; font-weight:700; }
    .btn-pay:hover { filter: brightness(1.05); }
  </style>

  <h2>Mis cuotas</h2>

  <p class="muted">
    Saldo a favor: <b>${{ number_format($u->wallet_balance ?? 0, 2, ',', '.') }}</b>
  </p>

  <p>
    <a href="{{ route('my.payments.new') }}">Cargar pago / comprobante</a>
  </p>

  <hr>

  @forelse($enrollments as $enr)
    <h3 style="margin:0 0 6px;">
      {{ $enr->cohort->course->title }} — {{ $enr->cohort->name }}
    </h3>

    <div class="muted" style="margin-bottom:10px;">
      Estado: {{ $enr->status }}
    </div>

    <div class="table-wrap">
      <table class="tbl">
        <tr>
          <th style="width:34px;"></th>
          <th style="width:40px;">#</th>
          <th style="width:140px;">Vence</th>
          <th>Debe</th>
          <th>Pagado</th>
          <th>Saldo</th>
          <th style="width:130px;">Estado</th>
          <th style="width:120px;">Acción</th>
        </tr>

        @foreach($enr->installments as $i)
          @php
            $due = $i->due_date ? \Illuminate\Support\Carbon::parse($i->due_date)->format('d/m/Y') : '—';
            $saldo = max(0, round((float)$i->amount_due - (float)$i->amount_paid, 2));

            $isPaid = $saldo <= 0.009 || ($i->status === 'paid');
            $isPartial = !$isPaid && ((float)$i->amount_paid > 0);

            $badgeClass = $isPaid ? 'badge-paid' : ($isPartial ? 'badge-partial' : 'badge-unpaid');
            $badgeText  = $isPaid ? 'Pagada' : ($isPartial ? 'Parcial' : 'Pendiente');

            $dotClass = $isPaid ? 'dot-paid' : ($isPartial ? 'dot-partial' : 'dot-unpaid');

            $ref = 'Cuota '.$i->number.' — '.$enr->cohort->course->title.' (vto '.$due.')';
          @endphp

          <tr class="{{ $isPaid ? 'row-paid' : '' }}">
            <td><span class="dot {{ $dotClass }}"></span></td>
            <td>{{ $i->number }}</td>
            <td>{{ $due }}</td>
            <td>${{ number_format($i->amount_due, 2, ',', '.') }}</td>
            <td>${{ number_format($i->amount_paid, 2, ',', '.') }}</td>
            <td>${{ number_format($saldo, 2, ',', '.') }}</td>
            <td><span class="badge {{ $badgeClass }}">{{ $badgeText }}</span></td>
            <td>
              @if($saldo > 0)
                <a class="btn-pay"
                  href="{{ route('my.payments.new', [
                    'enrollment_id' => $enr->id,
                    'installment_id' => $i->id,
                    'amount' => $saldo,
                    'reference' => $ref,
                  ]) }}">
                  Pagar
                </a>
              @else
                <span class="muted">—</span>
              @endif
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <hr>
  @empty
    <p>No tenés matrículas aún. Cuando te inscriban a una cohorte aparecerán tus cuotas acá.</p>
  @endforelse
@endsection
