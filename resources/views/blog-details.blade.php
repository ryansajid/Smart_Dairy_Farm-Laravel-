@extends('layouts.ogani')

@section('title', 'Ogani | Blog Details')

@section('content')
    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>All Categories</span>
                        </div>
                        <ul>
                            <li><a href="#">Liquid Milk</a></li>
                            <li><a href="#">Yogurt (Doi) & Drinks</a></li>
                            <li><a href="#">Milk Powder & Cream</a></li>
                            <li><a href="#">Ghee & Butter</a></li>
                            <li><a href="#">Condensed & Evaporated Milk</a></li>
                            <li><a href="#">Cheese</a></li>
                            <li><a href="#">Other Traditional Items</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="#">
                                <div class="hero__search__categories">
                                    All Categories
                                    <span class="arrow_carrot-down"></span>
                                </div>
                                <input type="text" placeholder="What do yo u need?">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5>+65 11.188.888</h5>
                                <span>support 24/7 time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('img/breadcrumb.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Blog Details</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ url('/') }}">Home</a>
                            <a href="{{ url('/blog') }}">Blog</a>
                            <span>Blog Details</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7 order-md-1 order-1">
                    <div class="blog__details__text">
                        <div class="blog__details__title">
                            <h6>May 4, 2019</h6>
                            <h2>The Benefits of Vitamin D & How to Get It</h2>
                            <div class="blog__details__share">
                                <span>Share</span>
                                <ul>
                                    <li><a href="#" class="facebook"><i class="fa fa-facebook-square"></i></a></li>
                                    <li><a href="#" class="twitter"><i class="fa fa-twitter-square"></i></a></li>
                                    <li><a href="#" class="youtube"><i class="fa fa-youtube-square"></i></a></li>
                                    <li><a href="#" class="linkedin"><i class="fa fa-linkedin-square"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <img src="{{ asset('img/blog/details/bd-1.jpg') }}" alt="">
                        <p>Sed quia non numquam modi tempora indunt ut labore et dolore magnam aliquam quaerat voluptatem.
                            Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut
                            aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate
                            velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla
                            pariatur.</p>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                            voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate
                            non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et
                            dolorum fuga.</p>
                        <h3>The importance of vitamins for your body</h3>
                        <p>Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis
                            est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis
                            voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis
                            debitis aut rerum necessitatibus saepe eveniet.</p>
                    </div>
                    <div class="blog__details__content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="blog__details__author">
                                    <div class="blog__details__author__pic">
                                        <img src="{{ asset('img/blog/details/details-author.jpg') }}" alt="">
                                    </div>
                                    <div class="blog__details__author__text">
                                        <h5>Michael Scofield</h5>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                            incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="blog__details__author__social">
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                            <a href="#"><i class="fa fa-google-plus"></i></a>
                                            <a href="#"><i class="fa fa-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="blog__details__tags">
                                    <a href="#">Vegetable</a>
                                    <a href="#">Fruit</a>
                                    <a href="#">Healthy Food</a>
                                    <a href="#">Lifestyle</a>
                                </div>
                                <div class="blog__details__btns">
                                    <a href="#" class="blog__details__btns__item">
                                        <i class="fa fa-angle-left"></i> Prev Post
                                    </a>
                                    <a href="#" class="blog__details__btns__item">
                                        Next Post <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog__details__comment">
                        <h4>3 Comments</h4>
                        <div class="blog__details__comment__item">
                            <div class="blog__details__comment__item__pic">
                                <img src="{{ asset('img/blog/details/comment-1.jpg') }}" alt="">
                            </div>
                            <div class="blog__details__comment__item__text">
                                <span>Sep 08, 2020</span>
                                <h5>Brandon Kelley</h5>
                                <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit,
                                    sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam.</p>
                                <a href="#">Like</a>
                            </div>
                        </div>
                        <div class="blog__details__comment__item blog__details__comment__item--reply">
                            <div class="blog__details__comment__item__pic">
                                <img src="{{ asset('img/blog/details/comment-2.jpg') }}" alt="">
                            </div>
                            <div class="blog__details__comment__item__text">
                                <span>Sep 08, 2020</span>
                                <h5>Brandon Kelley</h5>
                                <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit,
                                    sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam.</p>
                                <a href="#">Like</a>
                            </div>
                        </div>
                        <div class="blog__details__comment__item">
                            <div class="blog__details__comment__item__pic">
                                <img src="{{ asset('img/blog/details/comment-3.jpg') }}" alt="">
                            </div>
                            <div class="blog__details__comment__item__text">
                                <span>Sep 08, 2020</span>
                                <h5>Brandon Kelley</h5>
                                <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit,
                                    sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam.</p>
                                <a href="#">Like</a>
                            </div>
                        </div>
                    </div>
                    <div class="blog__details__form">
                        <h4>Leave A Comment</h4>
                        <form action="#">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" placeholder="Name">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" placeholder="Email">
                                </div>
                                <div class="col-lg-12">
                                    <textarea placeholder="Message"></textarea>
                                    <button type="submit" class="site-btn">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 order-md-2 order-2">
                    <div class="blog__sidebar">
                        <div class="blog__sidebar__search">
                            <form action="#">
                                <input type="text" placeholder="Search...">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="blog__sidebar__item">
                            <h4>Categories</h4>
                            <ul>
                                <li><a href="#">All</a></li>
                                <li><a href="#">Beauty (20)</a></li>
                                <li><a href="#">Food (5)</a></li>
                                <li><a href="#">Life Style (9)</a></li>
                                <li><a href="#">Travel (10)</a></li>
                            </ul>
                        </div>
                        <div class="blog__sidebar__item">
                            <h4>Recent News</h4>
                            <div class="blog__sidebar__recent">
                                <a href="#" class="blog__sidebar__recent__item">
                                    <div class="blog__sidebar__recent__item__pic">
                                        <img src="{{ asset('img/blog/sidebar/sr-1.jpg') }}" alt="">
                                    </div>
                                    <div class="blog__sidebar__recent__item__text">
                                        <h6>09 Kinds Of Vegetables<br /> Protect The Liver</h6>
                                        <span>MAR 05, 2019</span>
                                    </div>
                                </a>
                                <a href="#" class="blog__sidebar__recent__item">
                                    <div class="blog__sidebar__recent__item__pic">
                                        <img src="{{ asset('img/blog/sidebar/sr-2.jpg') }}" alt="">
                                    </div>
                                    <div class="blog__sidebar__recent__item__text">
                                        <h6>Tips You To Balance<br /> Nutrition Meal Day</h6>
                                        <span>MAR 05, 2019</span>
                                    </div>
                                </a>
                                <a href="#" class="blog__sidebar__recent__item">
                                    <div class="blog__sidebar__recent__item__pic">
                                        <img src="{{ asset('img/blog/sidebar/sr-3.jpg') }}" alt="">
                                    </div>
                                    <div class="blog__sidebar__recent__item__text">
                                        <h6>4 Principles Help You Lose <br />Weight With Vegetables</h6>
                                        <span>MAR 05, 2019</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="blog__sidebar__item">
                            <h4>Search By</h4>
                            <div class="blog__sidebar__item__tags">
                                <a href="#">Apple</a>
                                <a href="#">Beauty</a>
                                <a href="#">Vegetables</a>
                                <a href="#">Fruit</a>
                                <a href="#">Healthy Food</a>
                                <a href="#">Lifestyle</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection