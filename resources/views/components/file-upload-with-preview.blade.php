TESTE
@if ($getPreviewUrl())
    @php
        $fileUrl = $getPreviewUrl();
    @endphp

    @if (Str::endsWith($getPreviewUrl(), '.pdf'))
        <iframe src="{{ $fileUrl }}" width="100%" height="600px"></iframe>
    @else
        <img src="{{ $fileUrl }}" style="max-width: 100%; height: auto;" />
    @endif
    {{ $fileUrl }}

@endif
