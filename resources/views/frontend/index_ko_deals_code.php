
        <!-- @if($num_todays_deal > 0)
            <div class="col-lg-2 d-none d-lg-block">
                <div class="flash-deal-box bg-white h-100">
                    <div class="title text-center p-2 gry-bg">
                        <h3 class="heading-6 mb-0">
                            {{ __('Todays Deal') }}
                            <span class="badge badge-danger">{{__('Hot')}}</span>
                        </h3>
                    </div>
                    <div class="flash-content c-scrollbar c-height">
                        @foreach (filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get() as $key => $product)
                            @if ($product != null)
                                <a href="{{ route('product', $product->slug) }}" class="d-block flash-deal-item">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col">
                                            <div class="img">
                                                <img class="lazyload img-fit" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->flash_deal_img) }}" alt="{{ __($product->name . '-' . $product->unit_price) }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="price">
                                                <span class="d-block">{{ home_discounted_base_price($product->id) }}</span>
                                                @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                    <del class="d-block">{{ home_base_price($product->id) }}</del>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif -->