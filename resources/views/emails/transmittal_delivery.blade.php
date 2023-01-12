@component('mail::message')
  <h1>Dear, {{ $transmittals->receiver->full_name }}</h1>

  Anda mendapatkan Transmittal Form dari <b>{{ $transmittals->user->full_name }}</b> dengan
  nomor <br>
  # {{ $transmittals->receipt_full_no }}
  dengan rincian pengiriman sebagai berikut:

  @component('mail::table')
    <table width=100%>
      <thead>
        <tr>
          <td style="text-align: center">Status</td>
          <td style="text-align: center">Date</td>
          <td style="text-align: center">By</td>
          <td style="text-align: center">Project</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($deliveries as $delivery)
        <tr>
          <td style="text-align: center">{{ $delivery->delivery_status }}</td>
          <td style="text-align: center">{{ $delivery->delivery_date }}</td>
          <td style="text-align: center">{{ $delivery->user->full_name }}</td>
          <td style="text-align: center">{{ $delivery->user->project->project_code }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>    
  @endcomponent

  Untuk melihat detail Transmittal Form dan pengirimannya, klik tombol dibawah ini:
  @component('mail::button', ['url' => $link, 'color' => 'success'])
    Detail
  @endcomponent

  Terima kasih,<br>
  {{ config('app.name') }}
@endcomponent
