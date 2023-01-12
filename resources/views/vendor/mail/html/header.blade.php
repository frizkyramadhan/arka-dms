<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === config('app.name'))
        <img src="https://static.wixstatic.com/media/3ec77b_683fc2153510449e8a2239280a21e9ac~mv2.png/v1/fill/w_151,h_36,al_c,usm_0.66_1.00_0.01,enc_auto/3ec77b_683fc2153510449e8a2239280a21e9ac~mv2.png" width="170px" alt="ARKA Document Manager">
        @else
        {{ $slot }}
      @endif
    </a>
  </td>
</tr>
