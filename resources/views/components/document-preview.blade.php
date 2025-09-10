@if ($getState())
    @if (Str::endsWith($getState(), '.pdf'))
        <iframe src="{{ $getState() }}" width="100%" height="600px"></iframe>
    @else
        <img src="{{ $getState() }}" style="max-width: 100%; height: auto;">
    @endif

@endif

