<div class="col-md-6">
    <ol id="libraries-data-list">
        @foreach ($legislations as $legislation)
            <li class="library-data-item">
                <a href="{{ route('library.show', ['id' => $legislation->id, 'slug' => 'legislation']) }}" target="_blank">
                    {{ $legislation->title }}
                </a>
            </li>
        @endforeach
    </ol>
</div>
