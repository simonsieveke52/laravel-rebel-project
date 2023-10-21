<footer class="container footer-font">
    <div class="bg-med-grey text-dark py-2">
        <div class="d-flex flex-md-row flex-column justify-content-between align-items-center with 100%. bg-med-grey text-dark">
            <div class="col-md-6">
                <small>&copy; {{ now()->format('Y') }} - RebelSmuggling &nbsp;</small>
                <small><a href="{{ route('terms-and-conditions') }}" class="text-dark">Terms and Conditions</a></small>
                <small>|</small>
                <small><a href="{{ route('privacy-policy') }}" class="text-dark">Privacy Policy</a></small>
            </div>
            <div class="text-md-left text-center col-md-6">
                <div class="d-flex align-items-center justify-content-center justify-content-md-end flex-wrap small mr-auto ml-0 w-100">
                    <span>Site built, maintained, and marketed by FountainheadME.com &nbsp;&nbsp;</span>

                    <ul class="list-inline d-inline mb-0">
                        <li class="list-inline-item"><i class="fab fa-2x fa-cc-amex"></i></li>
                        <li class="list-inline-item"><i class="fab fa-2x fa-cc-discover"></i></li>
                        <li class="list-inline-item"><i class="fab fa-2x fa-cc-mastercard"></i></li>
                        <li class="list-inline-item"><i class="fab fa-2x fa-cc-visa"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>