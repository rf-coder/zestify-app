@extends('layouts.web')
@section('title', 'Products') 

@section('content')

<div class="container">

    <!-- Navbar for Categories -->
    <nav class="navbar navbar-light navbar-custom">
        <div class="container-fluid">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myNavbar">
                <span class="navbar-toggler-icon"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="navbar-nav">
                    <li class="{{ Route::currentRouteName() === 'products' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('products') }}">All Products</a>
                    </li>
                    @foreach (\App\Models\Category::all() as $category)
                        <li class="{{ Route::currentRouteName() === 'products.category' && request()->segment(3) === $category->name ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('products.category', $category->name) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>

    <nav class="navbar search">
        <div class="container-fluid">
            <form class="d-flex position-relative" role="search" method="GET" action="{{ route('products.search') }}">
                <input class="form-control me-2" type="search" id="search-bar" name="query" placeholder="Search" aria-label="Search" autocomplete="off" value="{{ request('query') }}">
                <button class="btn" type="submit">Search</button>
            </form>
        </div>
    </nav>
            <div id="products" class="row mt-0">
                <!-- Results will be displayed here dynamically -->
            </div>

    <br>

    <section class="hero text-center">
        <div class="hero-image">
            <img src="{{ asset('img/banner.png') }}" class="img-fluid w-100" alt="Hero Image">
        </div>
        <div class="hero-text">
            <h1>Our Products</h1>
            <p>Discover the best skincare, haircare, makeup, and cosmetic products.</p>
        </div>
    </section>

    <!-- Product Sections -->
    <section id="all-products" class="my-5">
        <div id="products-container" class="row">
            @forelse ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Rs. {{ $product->price }}/-</p>
                            <p class="card-text" id="description-{{ $product->id }}">
                                {{ Str::limit($product->description, 100) }} 
                                <a href="javascript:void(0);" class="text-decoration-none" onclick="toggleDescription({{ $product->id }})">Read More</a>
                            </p>
                            <p class="full-description d-none" id="full-description-{{ $product->id }}">
                                {{ $product->description }}
                                <a href="javascript:void(0);" class="text-decoration-none" onclick="toggleDescription({{ $product->id }})">Read Less</a>
                            </p>
                            <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </form><br>
                            <a href="{{ route('product-detail', $product->id) }}" class="btn btn-secondary d-flex align-items-center">
                                <i class="fas fa-eye me-2"></i> View Product
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No products found in this category.</p>
            @endforelse
        </div>
    </section>

    <!-- Button to scroll back to the top of the page -->
    <button id="goTopBtn" class="go-top"><i class="fas fa-arrow-up"></i></button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Toggle the visibility of full description
    function toggleDescription(productId) {
        var fullDescription = document.getElementById('full-description-' + productId);
        var shortDescription = document.getElementById('description-' + productId);

        if (fullDescription.classList.contains('d-none')) {
            fullDescription.classList.remove('d-none');
            shortDescription.classList.add('d-none');
        } else {
            fullDescription.classList.add('d-none');
            shortDescription.classList.remove('d-none');
        }
    }

    




</script>

@push('scripts')
        <script>
            $(document).ready(function() {
                $('#search-bar').on('keyup', function() {
                    let query = $(this).val();
                    if (query.length > 0) {
                        // Perform the AJAX request
                        $.ajax({
                            url: '/search',
                            method: 'GET',
                            data: { query: query },
                            success: function(response) {
                                // Update the results section with the response
                                $('#products').html(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                            }
                        });
                    } else {
                        $('#products').empty();
                    }
                });
            });
        </script>
    @endpush

<style>
    #search-results {
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
    }

    .result-item {
        padding: 10px;
        cursor: pointer;
    }

    .result-item:hover {
        background-color: #f1f1f1;
    }
</style>

@endsection
