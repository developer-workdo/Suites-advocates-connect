<div class="col-md-6">
    <ol id="libraries-data-list">
        @foreach ($researches as $research)
            <li class="library-data-item">
                <a href="{{ route('library.show', ['id' => $research->id, 'slug' => 'research']) }}" target="_blank">
                    {{ $research->title }}
                </a>
            </li>
        @endforeach
    </ol>
</div>
