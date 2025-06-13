@if(Auth::guard('siteuser')->check())
<style>
.giris {
  width: 550px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 550px;
  margin: 0 auto 100px;
  padding: 45px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form .tb {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
</style>
@livewire('listing-create',[$language_id])
@else
<div style="min-height: 53.6vh; display: flex; justify-content: center; align-items: center;">
<div class="alert alert-danger" role="alert" style="height: fit-content; width: 100%; text-align: center;">
  İlan Vermek İçin Öncelikle <a href="/tr/giris-yap">Giriş Yap</a>malı, Hesabın Yoksa <a href="/tr/kayit-ol">Kayıt Ol</a>malısın!
</div>
</div>
@endif