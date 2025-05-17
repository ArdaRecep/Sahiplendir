@php
    use App\Models\Language;
   $language = Language::findOrFail($language_id);
@endphp
<footer style="background-color: #fffd70 !important;">
    <div class="container">
       <div class="row">
          <div class="col-lg-6">
            <div style="width: 400px; display: flex; justify-content: center;">
             <a class="navbar-brand" href="/{{ $language->code }}">
                <img src="{{ $logo }}" style="width: 200px;">
            </a>
            </div>
             <p class="mt-3" style="max-width: 400px;">Sahiplendir, her türden canlının sevgi dolu bir yuvaya kavuşmasını amaçlayan ücretsiz bir sahiplendirme platformudur.
                Kâr amacı gütmeden, hayvanların daha iyi koşullarda yaşamasını ve sorumluluk sahibi insanlarla buluşmasını destekliyoruz.
                Siz de bir cana yuva olmak ya da bakamadığınız bir dostun daha iyi bir hayata adım atmasına aracılık etmek istiyorsanız, doğru yerdesiniz. Satın alma sahiplen!</p>
          </div>
          <div class="col-lg-3" style="margin-top: 149px!important;">
            <h6>Menü</h6>
            <ul class="list-unstyled">
               <x-footer-menu name="Footer Menu" :language_id="$language_id"/>
            </ul>
            <!--/ul -->
         </div>
          <!--/ col-lg -->
          <div class="col-lg-3" style="margin-top: 149px!important;">
             <h6><i class="fas fa-envelope margin-icon"></i>İletişim</h6>
             <ul class="list-unstyled">
                <li>(123) 456-789</li>
                <li><a href="mailto:email@yoursite.com">email@yoursite.com</a></li>
                <li>Pet Street 123 - New York </li>
             </ul>
             <ul class="list-unstyled" style="display: flex">
                <li><a><i class="fab fa-instagram" style="margin-right: 15px;"></i></a></li>
                <li><a><i class="fab fa-linkedin" style="margin-right: 15px;"></i></a></li>
                <li><a><i class="fab fa-pinterest" style="margin-right: 15px;"></i></a></li>
             </ul>
             <!--/ul -->
          </div>
          <!--/ col-lg -->
       </div>
       <!--/ row-->
       <div class="row">
          <div class="credits col-sm-12">
             <p>© 2025 <a href="/{{ $language->code }}">Sahiplendir</a> – Tüm hakları saklıdır.</p>
          </div>
       </div>
       <!--/ row -->
    </div>
    <!--/ container -->
    <!-- Go To Top Link -->
    <div class="d-none d-md-block">
       <a href="#top" class="back-to-top"><i class="fa fa-angle-up"></i></a>
    </div>
    <!--/page-scroll-->
 </footer>