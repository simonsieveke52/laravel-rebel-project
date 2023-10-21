<footer class="container footer-font">
    <div class="bg-red text-white pb-2">
        <hr class="py-1 m-0 mb-2">
        <div class="row">
            <div class="d-flex flex-md-row flex-column justify-content-between col-md-6 col-12 pb-3 pl-sm-5 pb-sm-5">
                    <div class="d-flex flex-column">
                        @foreach ($categories as $category)
                            @if($category->slug != "flavored-beverages" && $category->slug != "milk-and-nut-beverages")
                                <small><a href="{{ route('category.filter', $category) }}"  class="link-white text-capitalize footer-font-size-16">{{$category->name}}</a></small>
                            @endif
                        @endforeach
                    </div>
                    <div class="d-flex flex-column">
                        <small><a href="{{ route('about-us') }}" class="link-white footer-font-size-16">About Us</a></small>
                        <small><a href="{{ route('faq') }}" class="link-white footer-font-size-16">FAQ</a></small>
                        <small><a href="{{ route('shipping-return-policy') }}" class="link-white footer-font-size-16">Shipping & Returns</a></small>
                        <small><a href="{{ route('favorites') }}" class="link-white footer-font-size-16">Favorites</a></small>
                    </div>  
            </div>
            <div class="d-flex justify-content-md-end justify-content-start col-md-6 col-12 pr-md-5">
                <div class="d-flex flex-column">
                    <h1 class="h5">Office location</h1>
                    <p class="footer-font-size-16">4421 Giannecchini Lane #A<br>
                        Stockton, CA 95206<br><a class="text-white footer-font-size-16" href="mailto:support@rebelsmuggling.com">support@rebelsmuggling.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>