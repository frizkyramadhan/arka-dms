@component('mail::message')
  <h1>Dear, {{ $transmittals->receiver->full_name }}</h1>

  Anda mendapatkan Transmittal Form dari <b>{{ $transmittals->user->full_name }}</b> dengan
  nomor <br>
  # {{ $transmittals->receipt_full_no }}
  dengan rincian pengiriman sebagai berikut:

  @component('mail::table')
    | Status | Date | By | Project |
    |:---:|:---:|:---:|:---:|
    @foreach ($deliveries as $delivery)
      | {{ $delivery->delivery_status }} | {{ $delivery->delivery_date }} | {{ $delivery->user->full_name }} | {{ $delivery->user->project->project_code }} |
    @endforeach
  @endcomponent

  Untuk melihat detail Transmittal Form dan pengirimannya, klik tombol dibawah ini:
  @component('mail::button', ['url' => $link, 'color' => 'success'])
    Detail
  @endcomponent

  Terima kasih,<br>
  {{ config('app.name') }}
@endcomponent
