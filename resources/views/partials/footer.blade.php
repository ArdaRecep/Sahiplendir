@php
    use App\Models\Language;
   $language = Language::findOrFail($language_id);
@endphp
<footer style="background-color: #fffd70 !important;">
    <div class="container">
       <div class="row" style="justify-content: center;">
          <div class="col-lg-8 row" style="margin-top: 50px !important;">
            <div style="width: 270px; display: flex; justify-content: center;">
             <a class="navbar-brand" href="/{{ $language->code }}" style="display: flex;align-items: center;">
                <img src="/public{{ $logo }}" style="width: 200px; margin-right: 92px;">
            </a>
            </div>
             <p class="col-md-8 text-center">{!! $footer_text !!}</p>
          </div>
          <div class="col-lg-2" style="margin-top: 50px!important;">
            <h6 style="text-align: center;">{{ trans("theme/front.menu",[],$language->code) }}</h6>
            <ul style="text-align: center;" class="list-unstyled">
               <x-footer-menu name="Footer Menu" :language_id="$language_id"/>
            </ul>
            <!--/ul -->
         </div>
          <!--/ col-lg -->
          <div class="col-lg-2" style="margin-top: 50px!important;">
            <h6 style="text-align: center;">{{ trans("theme/front.popular_article",[],$language->code) }}</h6>
            <ul style="text-align: center;" class="list-unstyled">
               <x-footer-menu name="Popular Articles" :language_id="$language_id"/>
            </ul>
            <!--/ul -->
         </div>
          <!--/ col-lg -->
       </div>
       <!--/ row-->
       <div class="row">
          <div class="credits col-sm-12" style="margin-bottom: 35px!important;">
             <p>© 2025 <a href="/{{ $language->code }}">Sahiplendir</a> – All rights reserved.</p>
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