<div class="col-12 border-end border-bottom">
    <div class="bg-transparent shadow-none card sticky-top" style="top:70px">
        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
            <a href="{{ route('library.settings', 'library-settings') }}"
                class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'library-settings' ? 'active' : '' }}">
                {{ __('Library Settings') }}</a>
            @if (Auth::user()->type == 'company')
                <a href="{{ route('library.settings', 'case-law-by-area-categories') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'case-law-by-area-categories' ? 'active' : '' }}">
                    {{ __('Case Law By Area Categories') }}</a>
                <a href="{{ route('library.settings', 'case-law-by-areas') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'case-law-by-areas' ? 'active' : '' }}">
                    {{ __('Case Law By Areas') }}</a>
                <a href="{{ route('library.settings', 'legislations') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'legislations' ? 'active' : '' }}">
                    {{ __('Legislations') }}</a>
                <a href="{{ route('library.settings', 'journals') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'journals' ? 'active' : '' }}">
                    {{ __('Journals') }}</a>
                <a href="{{ route('library.settings', 'researches') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'researches' ? 'active' : '' }}">
                    {{ __('Researches') }}</a>
                <a href="{{ route('library.settings', 'practice-tool-categories') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'practice-tool-categories' ? 'active' : '' }}">
                    {{ __('Practice Tool Categories') }}</a>
                <a href="{{ route('library.settings', 'practice-tools') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'practice-tools' ? 'active' : '' }}">
                    {{ __('Practice Tools') }}</a>
                <a href="{{ route('library.settings', 'recent-development-categories') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'recent-development-categories' ? 'active' : '' }}">
                    {{ __('Recent Development Categories') }}</a>
                <a href="{{ route('library.settings', 'recent-developments') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ $type == 'recent-developments' ? 'active' : '' }}">
                    {{ __('Recent Developments') }}</a>
            @endif
        </div>
    </div>
</div>
