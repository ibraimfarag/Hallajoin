

<div class="container mt-5">
    <div class="bravo-list-locations @if (!empty($layout)) {{ $layout }} @endif">
        <div class="title title-line">
            {{ $title }}
        </div>
        @if (!empty($desc))
            <div class="sub-title">
                {{ $desc }}
            </div>
        @endif
        @if (!empty($rows))
            <div class="list-item owl-carousel locationowl">
                {{-- <div class="row  owl-carousel locationowl"> --}}
                    @foreach ($rows as $key => $row)
                        <?php
                        $size_col = 3;
                        if (!empty($layout) and ($layout == 'style_2' or $layout == 'style_3' or $layout == 'style_4')) {
                            $size_col = 12;
                        } else {
                            if ($key == 0) {
                                $size_col = 12;
                            }
                        }
                        ?>

                            {{-- <div class="col-lg-{{ $size_col }} col-md-12"> --}}
                                @include('Location::frontend.blocks.list-locations.loop')
                            {{-- </div> --}}
                    @endforeach
                {{-- </div> --}}
            </div>
        @endif
    </div>
</div>
