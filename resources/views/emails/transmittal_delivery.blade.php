 @component('mail::message')
 <h1>Dear, {{ $transmittals->receiver->full_name }}</h1>

 Anda mendapatkan Transmittal Form dari <b>{{ $transmittals->user->full_name }}</b> dengan
 nomor <br>
 <h1><b># {{ $transmittals->receipt_full_no }}</b></h1>
 dengan rincian sebagai berikut:

 @component('mail::table')
 <table width=100%>
   <thead>
     <tr>
       <td><b>Description</b></td>
       <td style="text-align: center"><b>Qty</b></td>
       <td style="text-align: center"><b>UoM</b></td>
       <td><b>Remarks</b></td>
     </tr>
   </thead>
   <tbody>
     @foreach ($transmittals->transmittal_details as $detail)
     <tr>
       <td>{{ $detail->description }}</td>
       <td style="text-align: center">{{ $detail->qty }}</td>
       <td style="text-align: center">{{ $detail->uom }}</td>
       <td>{{ $detail->remarks }}</td>
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
