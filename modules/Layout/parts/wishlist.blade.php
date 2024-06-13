@if(auth()->check())
    @php
        $wishlistData = getWishlist();
        $wishlistItems = $wishlistData['wishlist'];
        $countUnread = $wishlistData['count'];
    @endphp
    <style>
        .wishlist-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
            align-items: center;
        }

        .wishlist-item:hover {
            background-color: #f0f0f0;
        }

        .wishlist-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .wishlist-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .wishlist-item-details h6 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .wishlist-item-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .wishlist-item-details .location,
        .wishlist-item-details .price,
        .wishlist-item-details .rate {
            margin-top: 10px;
            font-size: 14px;
            color: #777;
        }

        .wishlist-item-details .control-action {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-remove {
            border: none;
            background: transparent;
            color: #f00;
            font-size: 20px;
            cursor: pointer;
        }
      
        .service-review {
            display: inline-block;
            position: relative;
            top: 2px;
        }
        
        .review {
            color: #768092;
            font-size: 13px;
            margin-right: 5px;
            position: relative;
            top: -2px;
        }

        .item-list .service-review .list-star .booking-item-rating-stars li {
            float: left;
            list-style: none;
            margin-right: 5px;
        }

        .item-list .service-review .list-star .booking-item-rating-stars {
            margin: 0;
            padding: 0;
            width: 90px;
        }

        .item-list .service-review .list-star .booking-item-rating-stars:before {
            clear: both;
            content: "";
            display: table;
        }

        .service-review .list-star {
            display: inline-block;
            line-height: 13px;
            position: relative;
        }

        .item-list .control-action {
            bottom: 15px;
            margin-right: 15px;
            position: absolute;
            right: 13px;
            text-align: right;
        }

        .editmenu li {
            overflow: hidden;
            padding-left: 10px !important;
            padding-right: 10px !important;
            width: 100%;
        }
    </style>

    <li class="dropdown-notifications dropdown p-0">
        <a href="#" data-toggle="dropdown" class="is_login">
            <i class="fa fa-heart mr-2"></i>
            <span class="badge badge-danger notification-icon">{{$countUnread}}</span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu editmenu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large">
            @foreach($wishlistItems as $item)
                @php
                    $service = $item->service;
                @endphp
                <li class="wishlist-item">
                    <div class="item-list">
                        @if($service->discount_percent)
                            <div class="sale_info">{{$service->discount_percent}}</div>
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                @if($service->is_featured == "1")
                                    <div class="featured">
                                        {{__("Featured")}}
                                    </div>
                                @endif
                                <div class="thumb-image">
                                    <a href="{{$service->getDetailUrl()}}" target="_blank">
                                        @if($service->image_url)
                                            <img src="{{$service->image_url}}" class="wishlist-item-image " alt=" {{$service->title}}">
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="item-title">
                                    <a href="{{$service->getDetailUrl()}}" target="_blank">
                                        <h6> {{$service->title}}</h6>
                                    </a>
                                </div>
                         
                                <div class="location">
                                    @if(!empty($service->location->name))
                                        <i class="icofont-paper-plane"></i>
                                        {{__("Location")}}: {{$service->location->name ?? ''}}
                                    @endif
                                </div>
                                <div class="location">
                                    <i class="icofont-money"></i>
                                    {{__("Price")}}: <span class="sale-price">{{ $service->display_sale_price }}</span> <span class="price">{{ $service->display_price }}</span>
                                </div>
                                @if($service->getReviewEnable())
                                    <div class="rate">
                                        <i class="icofont-badge"></i>
                                        <?php
                                        $reviewData = $service->getScoreReview();
                                        $score_total = $reviewData['score_total'];
                                        ?>
                                        <div class="service-review tour-review-{{$score_total}}">
                                            <span class="review">
                                                @if($reviewData['total_review'] > 1)
                                                    {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
                                                @else
                                                    {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                <div class="control-action">
                                    <a href="{{ route('user.wishList.remove',['id'=>$service->id , 'type' => $service->type]) }}" class="btn-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </li>
@else
    <li class="dropdown-notifications dropdown p-0">
        <a href="#" data-toggle="dropdown">
            <i class="fa fa-bell mr-2"></i>
            <span class="badge badge-danger notification-icon">0</span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a href="#">You must be logged in to view notifications</a>
            </li>
        </ul>
    </li>
@endif
