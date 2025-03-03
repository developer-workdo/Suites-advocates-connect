@php
    $fileExtension = pathinfo($libraryData->document, PATHINFO_EXTENSION);
    $fileUrl = Storage::url($libraryData->document);
    $siteUrl = $libraryData->site_link;
@endphp
@if (!empty($siteUrl))
    <script type="text/javascript">
        window.location.href = "{{ $siteUrl }}";
    </script>
@endif
@if (!empty($libraryData->description))
    {!! $libraryData->description !!}
@endif
@if (isset($libraryData->document) && !empty($libraryData->document))
    @if ($fileExtension === 'pdf')
        <iframe
            src="{{ Storage::exists($libraryData->document) ? $fileUrl : Storage::url('not-exists-data-images/78x78.png') }}"
            width="100%" height="100%"></iframe>
    @elseif ($fileExtension === 'docx' || $fileExtension === 'doc' || $fileExtension === 'rtf')
        @php
            $encodedFileUrl = urlencode($fileUrl);
            $googleDocsViewerUrl = "https://docs.google.com/gview?url={$encodedFileUrl}&embedded=true";
        @endphp
        <iframe src="{{ $googleDocsViewerUrl }}" frameborder="0" style="width:100%;height: 100%;"></iframe>
    @else
        <p>{{ __('Unsupported file type') }}: {{ $fileExtension }}</p>
    @endif
@endif
