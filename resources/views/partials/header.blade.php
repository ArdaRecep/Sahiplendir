@php
    use App\Models\Language;
    $language = Language::findOrFail($language_id);
@endphp
<nav id="main-nav" class="navbar-expand-xl">
    <!-- Navbar Starts -->
    <div class="navbar container-fluid" style="background-color: #fffd70 !important;">
        <div class="container">
            <!-- logo -->
            <a class="navbar-brand" href="/{{ $language->code }}">
                <img src="{{ $footer_logo }}" style="width: 240px;">
            </a>
            <!-- Navbartoggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggle-icon">
                    <i class="fas fa-bars"></i>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <x-menu name="Navigation Menu" :language_id="$language_id" />
                    @livewire('language-menu', ['page' => $page])
                </ul>
                <!--/ul -->
            </div>

            @if (Auth::guard('siteuser')->check())
                <div class="flex items-center space-x-4" style="margin-left: 5px">
                    <a
                        href="/tr/hesap"><span><strong>{{ Auth::guard('siteuser')->user()->username }}</strong></span></a>
                    @livewire('logout')
                </div>
                <a href="/messages" style="margin-left: 15px;"><i class="far fa-bell" style="color: black;"></i></a>
            @else
                <a href="/tr/giris-yap" class="text-sm text-blue-600 hover:underline" style="margin-left: 5px">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            @endif
            <!--collapse -->
        </div>
        <!-- /container -->
    </div>
    <!-- /navbar -->
</nav>
