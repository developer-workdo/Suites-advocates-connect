<div class="col-md-6">
    <ol id="libraries-data-list">
        @foreach ($journals as $journal)
            <li class="library-data-item">
                <a href="{{ route('library.show', ['id' => $journal->id, 'slug' => 'journal']) }}" target="_blank">
                    {{ $journal->title }}
                </a>
            </li>
        @endforeach
    </ol>
</div>
