<style>
    video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  min-width: 100%;
  min-height: 100%;
  z-index: -1;
  object-position: center;
  object-fit: cover;
}
</style>

<div class="container">
    <div class="bravo_gallery">
        <div class="btn-group">
            <span class="btn-transparent has-icon">
                @if ($bg_image)
                    {{-- <img src="{{get_file_url($bg_image,'full')}}" class="img-fluid" alt="Youtube"> --}}
                @endif
                @if ($youtube)
                    <div class="play-icon">
                        <img src="{{ asset('module/vendor/img/ico-play.svg') }}" alt="Play background"
                            class="img-fluid play-image">
                    </div>
                @endif
            </span>
        </div>
        @if ($youtube)
            <div class="embed-responsive" style="height: 350px;border-radius: 30px;">
    
                    <video autoplay muted loop src="{{ handleVideoUrl($youtube) }}">
                    </video>

            </div>
        @endif
    </div>
</div>
