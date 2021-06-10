<div class="mb-hash">
    #мылицаfitme
</div>
<div class="mb-gallery" data-url="">
    @if ($posts->count() > 0)
        @foreach ($posts as $post)
            <div class="mb-gallery__item"
                 style="background-image: url({{ $post['media']['cover'] }})"></div>
        @endforeach
    @endif
</div>
