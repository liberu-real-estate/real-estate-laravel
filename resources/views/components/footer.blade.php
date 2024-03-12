<footer class="bg-light text-center text-lg-start">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">{{ config('app.name') }}</h5>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Quick Links</h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ url('/') }}" class="text-dark">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="text-dark">About Us</a></li>
                    <li><a href="{{ url('/properties/featured') }}" class="text-dark">Featured Properties</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-dark">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contact Us</h5>
                <p>Email: info@liberurealestate.com</p>
                <p>Phone: +123 456 7890</p>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        Made with <span style="color: #e25555;">&hearts;</span> by the Liberu Real Estate Team
    </div>
</footer>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
