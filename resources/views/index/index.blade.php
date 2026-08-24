@extends('index.layout')
@section('content')
    <style>
        /* Carousel Ken Burns Effect */
        .carousel-item img {
            animation: zoomIn 15s linear infinite;
            transform-origin: center center;
        }

        @keyframes zoomIn {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Category Blocks Interactive Styling */
        .category-block {
            display: block;
            text-decoration: none;
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 20px;
            border: 1px solid #f2f2f2;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            /* Bouncy hover */
        }

        .category-block:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            transform: translateY(-10px);
            border-color: transparent;
        }

        .category-block .img-fluid {
            transition: transform 0.4s ease-in-out;
        }

        .category-block:hover .img-fluid {
            transform: scale(1.08);
        }

        .collection-title {
            display: block;
            margin-top: 20px;
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
            transition: color 0.3s;
        }

        .category-block:hover .collection-title {
            color: #165b38;
            /* Brand Green */
        }

        /* Premium Banner */
        .premium-banner {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 40px 20px;
            transition: box-shadow 0.3s ease;
        }

        .premium-banner:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }

        .premium-banner img {
            filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.15));
            transition: transform 0.5s ease-in-out;
        }

        .premium-banner:hover img {
            transform: scale(1.03);
        }
    </style>
    <div class="body-wrapper">
        <main id="MainContent" class="content-for-layout">
            <!-- Hero Swiper Carousel -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
            <style>
                .myHeroSwiper {
                    padding: 40px 20px 60px 20px;
                    background-color: #f8f9fa;
                }

                .myHeroSwiper .swiper-slide {
                    transition: transform 0.3s;
                    background-color: #fff;
                    border-radius: 15px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    height: 380px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    padding: 15px;
                    /* Ensures image never touches the outer edge */
                }

                .myHeroSwiper .swiper-slide:hover {
                    transform: translateY(-10px);
                }

                .myHeroSwiper img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    /* Keeps the whole image visible */
                    transform: scale(1.8);
                    /* Significantly zooms in to eliminate baked-in empty space */
                }

                .myHeroSwiper .swiper-pagination-bullet-active {
                    background: #165b38;
                }
            </style>

            <div class="swiper myHeroSwiper" data-aos="fade-down" data-aos-duration="1000">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('index') }}/assets/img/bannerimg2.png" alt="Banner 1">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('index') }}/assets/img/1.png" alt="Banner 2">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('index') }}/assets/img/2.png" alt="Banner 3">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('index') }}/assets/img/3.png" alt="Banner 4">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('index') }}/assets/img/4.png" alt="Banner 5">
                    </div>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Swiper('.myHeroSwiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            }
                        }
                    });
                });
            </script>
            <!-- /Hero Swiper Carousel -->
            <!-- trusted badge start -->
            <div class="mt-60 home-section">
                <div class="section-category-slider">
                    <div class="container">
                        <div class="row justify-content-center">
                            @foreach ($uniformCats as $cat)
                                @php
                                    // Ensure both id and slug are being passed correctly
                                    $url = route('category.show', [$cat->id, Str::slug($cat->name)]);
                                    $img =
                                        $cat->name == 'Summer Uniform'
                                            ? asset('index/assets/img/summeruniform.png')
                                            : asset('index/assets/img/winteruniform.png');
                                @endphp

                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-duration="800"
                                    data-aos-delay="{{ $loop->iteration * 100 }}">
                                    <a href="{{ $url }}"
                                        class="category-block category-block-2 heading_18 medium text-center">
                                        <img src="{{ $img }}" alt="{{ $cat->name }}" class="img-fluid">
                                        <span class="collection-title">{{ $cat->name }}</span>
                                    </a>
                                </div>
                            @endforeach
                            {{-- Accessories static block --}}
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-duration="800"
                                data-aos-delay="500">
                                <a href="{{ route('accessories') }}"
                                    class="category-block category-block-2 heading_18 medium text-center">
                                    <img src="{{ asset('index/assets/img/accessories.png') }}" alt="Accessories"
                                        class="img-fluid">
                                    <span class="collection-title">Accessories</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- trusted badge end -->


            <!-- latest blog start -->
            <div class="latest-blog-section mt-5 mb-5 overflow-hidden home-section" data-aos="zoom-in"
                data-aos-duration="1000">
                <div class="latest-blog-inner">
                    <div class="container">
                        <div class="text-center premium-banner shadow-sm">
                            <img src="{{ asset('index') }}/assets/img/bannerimg2.png" class="img-fluid"
                                alt="Learning Alliance Uniforms" style="max-height: 500px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- latest blog end -->
        </main>



        <!-- scrollup start -->
        <button id="scrollup">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </button>
        <!-- scrollup end -->



        <!-- drawer menu end -->


    </div>

    <script>
        $(document).ready(function() {
            // Trigger on Quickview button click
            $(".action-quickview").on('click', function() {
                var productId = $(this).data('id'); // Get product ID

                // Fetch product details using AJAX or any other method
                $.ajax({
                    url: '/get-product-details', // Your endpoint to fetch product details
                    method: 'GET',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        // Populate the modal with product data
                        $('#product-title').text(response.title);
                        $('#product-price .regular-price').text('$' + response.price);
                        $('#product-image').attr('src', response.main_image);

                        // Populate Product Thumbnails
                        var thumbnailsHTML = '';
                        response.thumbnails.forEach(function(img) {
                            thumbnailsHTML +=
                                '<div><div class="img-thumb-wrapper"><img src="' + img +
                                '" alt="img" /></div></div>';
                        });
                        $('.qv-thumb-slider').html(thumbnailsHTML);

                        // Inject Product Variants (size, color)
                        var variantHTML = '';
                        response.variants.forEach(function(variant) {
                            variantHTML +=
                                '<li class="variant-item"><input type="radio" value="' +
                                variant.value + '" /><label class="variant-label">' +
                                variant.label + '</label></li>';
                        });
                        $('.product-variant-wrapper').html(variantHTML);
                    }
                });
            });
        });
    </script>
@endsection
