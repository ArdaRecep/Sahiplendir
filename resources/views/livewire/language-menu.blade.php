<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" id="language-switch" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-globe"></i>
    </a>

    <div class="dropdown-menu" aria-labelledby="language-switch">
        @foreach ($languagesWithSlugs as $language)
            <a class="dropdown-item"
                href="/{{ $language['name'] }}/@if (isset($language['slug']) && isset($language['published_at'])){{ $language['slug'] }} @else#sorry @endif">
                {{ $language['title'] }}
            </a>
        @endforeach
    </div>
</li>
