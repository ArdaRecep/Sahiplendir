@php
    if ($page->status == 'inactive') {
        abort(404);
    }
@endphp
@extends('layouts.master')


@section('content')
    @use('App\Models\Listing', 'Listing')
    @use('App\Models\City', 'City')
    @use('App\Models\District', 'District')
    @use('App\Models\Neigborhood', 'Neigborhood')
    @use('App\Models\Quarter', 'Quarter')
    @use('App\Models\Category', 'Category')
    @use('App\Models\SubCategory', 'SubCategory')
    @php
        $language_id = $page->language_id;
        $city = City::findOrFail($page->city_id)->name;
        $district = District::findOrFail($page->district_id)->name;
        $neigborhood = Neigborhood::findOrFail($page->neigborhood_id)->name;
        $quarter = Quarter::findOrFail($page->quarter_id)->name;
        $category = Category::findOrFail($page->category_id)->name;
        $subCategory = SubCategory::findOrFail($page->sub_category_id)->name;
        $recipient_id = $page->user->id;
    @endphp


    <style>
        .listing-detail {
            margin-top: 2rem;
        }

        .listing-detail .card {
            border: none;
            border-radius: .75rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgb(0, 0, 0);
        }

        .listing-detail .card__cover img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .listing-detail .info-label {
            font-weight: 600;
            color: #555;
        }

        .user-card {
            border: 1px solid #e3e3e3;
            border-radius: .75rem;
            padding: 1rem;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.7);
            background-color:
        }

        .listing-detail dt {
            font-weight: 600;
        }

        .listing-detail dd {
            margin-bottom: 1rem;
        }
    </style>

    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row bg-light-custom border-irregular1 block-padding">
                        <!-- image -->
                        <div class="col-lg-4" style="margin-right: 35px; margin-left: 35px;">
                            <!-- owl slider -->
                            <div id="owl-adopt-single" class="owl-carousel owl-theme">
                                @foreach ($page->photos as $photo)
                                    <div class="col-md-12" style="display: flex;justify-content: center;">
                                        <!-- image -->
                                        <a href="/storage/{{ $photo }}" title="{{ $page->title }}">
                                            <img src="/storage/{{ $photo }}" style="height: 410px; width: 410px;"
                                                class="border-irregular1 img-fluid hover-opacity" alt="{{ $page->title }}">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <!-- /owl-carousel -->
                        </div>
                        <!-- /col-md -->
                        <!-- adoption info  -->
                        <div class="col-lg-4 res-margin mt-5 text-xs-center">
                            <h4 style="color: black; overflow-wrap: break-word;"><strong>İsmi:</strong> {{ $page->title }}
                            </h4>
                            <p class="mb-4"><strong>Konum:</strong>
                                {{ $city . '/' . $district . '/' . $neigborhood . '/' . $quarter }}</p>
                            <p class="mb-4"><strong>İlan no:</strong> <a
                                    href="/tr/{{ $page->listing_no }}">{{ $page->listing_no }}</a></p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled pet-adopt-info">
                                        <li class="h7">Cinsiyeti: <span>{{ $page->data['gender'] }}</span></li>
                                        <li class="h7">Türü: <span>{{ $category }}</span></li>
                                        <li class="h7">Kısır: <span>{{ $page->data['neutered'] }}</span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled pet-adopt-info">
                                        <li class="h7">Yaşı: <span>{{ $page->data['age'] }}</span></li>
                                        <li class="h7">Cinsi: <span>{{ $subCategory }}</span></li>
                                        <li class="h7">Boyutu: <span>{{ $page->data['size'] }}</span></li>
                                    </ul>
                                </div>
                                <!-- /div-->
                            </div>
                        </div>
                        <div class="col-lg-3 "
                            style="margin-right: 35px; display: flex; justify-content: center; align-items: center;">
                            <div class="user-card" style="padding: 70px 20px 70px 20px">
                                <h5 class="mb-4" style="text-align: center;">İlan Sahibi</h5>
                                <div class="d-flex mb-4 align-items-center justify-content-center">
                                    <div class="me-3">
                                        <img src="/storage/{{ $page->user->profile_photo ?? asset('front/img/default-user.png') }}"
                                            alt="{{ $page->user->name }}" class="rounded-circle"
                                            style="width:60px; height:60px; object-fit:fill;">
                                    </div>
                                    <div>
                                        <strong style="color: black" class="mb-0">{{ $page->user->name }}</strong><br>
                                        <a href="tel:{{ $page->user->phone }}"><small
                                                class="text-muted">{{ $page->user->phone }}</small></a>
                                        <a href="mailto:{{ $page->user->email }}"><small
                                                class="text-muted">{{ $page->user->email }}</small></a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#chatModal">
                                    Mesaj Gönder
                                </button>

                                @php
                                    use App\Models\SimpleMessage;
                                    if (isset(Auth::guard('siteuser')->user()->id)) {
                                        $me = Auth::guard('siteuser')->user()->id;
                                        $owner = $page->user->id;
                                        // Mesajları al
                                        $conversation = SimpleMessage::query()
                                            ->whereIn('sender_id', [$me, $owner])
                                            ->whereIn('recipient_id', [$me, $owner])
                                            ->orderBy('created_at')
                                            ->get();
                                    }
                                @endphp
                                @if (isset(Auth::guard('siteuser')->user()->id))
                                    <div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Mesajlaşma: {{ $page->user->name }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body" style="max-height:400px; overflow-y:auto;">
                                                    @forelse($conversation as $msg)
                                                        <div
                                                            class="mb-3 {{ $msg->sender_id === $me ? 'text-end' : 'text-start' }}">
                                                            <small class="text-muted">
                                                                {{ $msg->created_at->format('H:i d.m.Y') }} —
                                                                <strong>{{ $msg->sender_id === $me ? 'Siz' : $msg->sender->name }}</strong>
                                                            </small>
                                                            <div
                                                                class="d-inline-block p-2 rounded 
               {{ $msg->sender_id === $me ? 'bg-primary text-white' : 'bg-light' }}">
                                                                {{ $msg->body }}
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-center text-muted">Henüz mesaj yok.</p>
                                                    @endforelse
                                                </div>
                                                @if ($me !== $owner)
                                                    <div class="modal-footer">
                                                        @php
                                                            $prefix = $page->title . ': ';
                                                        @endphp
                                                        <form action="{{ route('message.send') }}" method="POST"
                                                            class="w-100 d-flex">
                                                            @csrf
                                                            <input type="hidden" name="recipient_id"
                                                                value="{{ $owner }}">
                                                            <textarea id="body" type="text" name="body" class="form-control me-2" required>{{ $prefix }}</textarea>
                                                            <button type="submit" class="btn btn-primary">Gönder</button>
                                                        </form>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                const prefix = @json($prefix);
                                                                const textarea = document.getElementById('body');

                                                                // 1) İlk yüklemede prefix’i kesinleştir
                                                                if (!textarea.value.startsWith(prefix)) {
                                                                    textarea.value = prefix;
                                                                }

                                                                // 2) Cursor başlangıç pozisyonunu prefix sonrası koy
                                                                function setCursorToEnd() {
                                                                    const pos = textarea.value.length;
                                                                    textarea.setSelectionRange(pos, pos);
                                                                }
                                                                setCursorToEnd();

                                                                // 3) Prefix’in silinmesini engelle
                                                                textarea.addEventListener('keydown', function(e) {
                                                                    const start = textarea.selectionStart;
                                                                    // Backspace
                                                                    if (e.key === 'Backspace' && start <= prefix.length) {
                                                                        e.preventDefault();
                                                                    }
                                                                    // Delete
                                                                    if (e.key === 'Delete' && start < prefix.length) {
                                                                        e.preventDefault();
                                                                    }
                                                                });

                                                                // 4) Mouse ile prefix kısmına tıklanıp seçilmesini engelle
                                                                textarea.addEventListener('mousedown', function(e) {
                                                                    setTimeout(() => {
                                                                        if (textarea.selectionStart < prefix.length) {
                                                                            setCursorToEnd();
                                                                        }
                                                                    }, 0);
                                                                });

                                                                // 5) Her input’ta prefix kontrolü, eğer silinmişse geri ekle
                                                                textarea.addEventListener('input', function(e) {
                                                                    if (!textarea.value.startsWith(prefix)) {
                                                                        const content = textarea.value;
                                                                        textarea.value = prefix + content.slice(prefix.length);
                                                                    }
                                                                });

                                                                // 6) Form gönderilirken de prefix’i koru (ekstra güvenlik)
                                                                textarea.form.addEventListener('submit', function() {
                                                                    if (!textarea.value.startsWith(prefix)) {
                                                                        textarea.value = prefix + textarea.value;
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Mesajlaşma: {{ $page->user->name }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body" style="max-height:400px; overflow-y:auto;">
                                                    <a href="/tr/giris-yap" style="text-decoration:underline;">Giriş
                                                        yap</a>madan mesaj gönderemezsiniz!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <h3>{{ $page->title }} hakkında</h3>
                        <!-- ul custom-->
                        <ul class="custom list-inline font-weight-bold">
                            @if ($page->data['otherAnimals'] == 'Evet')
                                <li class="list-inline-item">Diğer hayvanlarla iyi geçinir</li>
                            @endif
                            @if ($page->data['apartment'] == 'Evet')
                                <li class="list-inline-item">Apartman dairesinde yaşamaya uygun</li>
                            @endif
                        </ul>
                        <p> {{ $page->description }} </p>
                        <!-- /alert-->
                    </div>
                    <!-- /col-md-->

                </div>
                <!-- /col-lg-->
            </div>
            <!-- /row-->
        </div>
        <!-- /container-->
        @if (session('swal'))
            <script>
                Swal.fire({
                    title: @json(session('swal.title')),
                    html: @json(session('swal.html')),
                    icon: @json(session('swal.icon', 'success')),
                    confirmButtonText: @json(session('swal.confirmButtonText', 'Tamam')),
                });
            </script>
        @endif


    </div>
@endsection
