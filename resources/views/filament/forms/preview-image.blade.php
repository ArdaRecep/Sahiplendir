@if($this->preview_image_url)
<div >
    <img src="{{ asset('storage/' . ($this->preview_image_url ?? '')) }}" alt="Preview Image">
</div>
@endif



