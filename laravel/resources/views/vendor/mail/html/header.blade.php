@props(['url'])
<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      <!-- SITA online logo -->
      <img src="{{ asset('imgs/logo.png')}}" class="logo" alt="{{ $slot }} Logo" />
    </a>
  </td>
</tr>
