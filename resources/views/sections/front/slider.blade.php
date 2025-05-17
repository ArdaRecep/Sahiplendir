<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
/>
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    @foreach($section->data['slider'] as $item)
      <div class=" swiper-slide ls-l  header-wrapper" style="display: flex; align-items: center; background-image:url('/storage/{{ $item['image'] }}')" data-ls="duration:4000; transition2d:7; kenburnszoom:out; kenburnsrotate:-5; kenburnsscale:1.2;">
        <div class="header-text cat-elements">
          <h2>{{ $item['title'] }}</h2>
          <div class="d-none d-sm-block">
          <p class="header-p">{{ $item['text'] }}</p>
          <a class="btn btn-primary" href="{{ $item['btn_link'] }}">{{ $item['btn_text'] }}</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="swiper-pagination"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,               // sürekli döngü
    autoplay: {
      delay: 5000,            // 5 saniyede bir geçiş
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    effect: "fade",           // hoş bir geçiş efekti
    fadeEffect: { crossFade: true },
  });
</script>