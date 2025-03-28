@props(['url'])

<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
            @if (trim($slot) === 'Homize')
                <img src="{{ asset('images/homizelogoblue.png') }}" class="logo" alt="Homize Logo">
            @else
                <span style="font-size: 24px; font-weight: bold; color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">{{ $slot }}</span>
            @endif
        </a>
    </td>
</tr>