<!-- navbar-->
<header class="header bg-white">
    <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0"><a class="navbar-brand" href="index.html"><span
                    class="font-weight-bold text-uppercase text-dark">{{config('app.name')}}</span></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link active" href="{{route('front.index')}}">Home</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="{{route('frontend.shop')}}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="{{route('frontend.product')}}">Product detail</a>
                    </li>
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="pagesDropdown" href="#"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                        <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a
                                class="dropdown-item border-0 transition-link" href="index.html">Homepage</a><a
                                class="dropdown-item border-0 transition-link" href="shop.html">Category</a><a
                                class="dropdown-item border-0 transition-link" href="detail.html">Product
                                detail</a><a class="dropdown-item border-0 transition-link" href="cart.html">Shopping
                                cart</a><a class="dropdown-item border-0 transition-link"
                                href="checkout.html">Checkout</a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <livewire:frontend.carts />
                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{route('login')}}"> <i
                                class="fas fa-user-alt mr-1 text-gray"></i>Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('register')}}"> <i
                                class="fas fa-user-alt mr-1 text-gray"></i>Register</a></li>
                    @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" role="button" id="authDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-alt mr-1 text-gray"></i>
                            Welcom , {{auth()->user()->full_name}}
                            {{-- Welcom , {{auth()->user()->first_name . ' ' . auth()->user()->last_name }} --}}
                        </a>
                        <div class="dropdown-menu mt-3" aria-labelledby="authDropdown">
                            <a href="{{route('customer.profile')}}" class="dropdown-item border-0">{{__('My profile')}}</a>
                            <a href="javascript:void(0);" class="dropdown-item border-0"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                            <form action="{{route('logout')}}" method="POST" id="logout-form" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest


                </ul>
            </div>
        </nav>
    </div>
</header>
