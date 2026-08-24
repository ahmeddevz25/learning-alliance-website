      <style>
          /* Mega container */
          .has-dropdown.mega {
              position: relative;
          }

          .has-dropdown.mega .mega-wrap {
              position: absolute;
              left: 0;
              right: 0;
              top: 100%;
              background: #fff;
              border: 1px solid #e5e5e5;
              box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
              padding: 0;
              z-index: 40;
              display: none;
          }

          /* Show on hover (desktop) */
          .has-dropdown.mega:hover>.mega-wrap {
              display: block;
          }

          /* Column base */
          .mega-col {
              position: relative;
              min-width: 220px;
          }

          .root-col {
              display: block;
              background: #f2f4f7;
              padding: 0;
          }

          .root-col>li {
              position: relative;
          }

          .root-col>li>a {
              display: block;
              padding: 10px 14px;
          }

          /* Flyout columns */
          .flyout {
              position: absolute;
              top: 0;
              left: 100%;
              background: #fff;
              min-width: 260px;
              border-left: 1px solid #e5e5e5;
              display: none;
              padding: 6px 0;
          }

          .lvl-2 {
              /* next to col-1 */
          }

          .lvl-3 {
              left: 100%;
          }

          /* stacks to the right of lvl-2 */

          /* Open flyouts on hover of parent li */
          .root-col>li:hover>.lvl-2 {
              display: block;
          }

          .lvl-2>li:hover>.lvl-3 {
              display: block;
          }

          /* Hover styles */
          .root-col>li:hover>a,
          .flyout>li:hover>a {
              background: #eef3ff;
              color: #0b5ed7;
          }

          /* Keep lists tidy */
          .submenu.mega-col li a {
              white-space: nowrap;
          }

          /* flyouts ko cut na karo */
          .mega-wrap {
              overflow: visible;
          }

          /* har level ka parent <li> positioned ho */
          .mega-wrap .root-col>li,
          .mega-wrap .lvl-2>li {
              position: relative;
          }

          /* L2 flyout: parent li ke right me, SAME top se start */
          .mega-wrap .root-col>li>.lvl-2 {
              position: absolute;
              top: 0;
              /* 👈 parent <li> ke top se */
              left: 100%;
              display: none;
              min-width: 260px;
              background: #fff;
              border-left: 1px solid #e5e5e5;
              z-index: 50;
          }

          /* L3 flyout: uske parent (lvl-2 ka li) ke right me, SAME top */
          .mega-wrap .lvl-2>li>.lvl-3 {
              position: absolute;
              top: 0;
              /* 👈 parent <li> ke top se */
              left: 100%;
              display: none;
              min-width: 260px;
              background: #fff;
              border-left: 1px solid #e5e5e5;
              z-index: 60;
          }

          /* show on hover */
          .mega-wrap .root-col>li:hover>.lvl-2 {

              display: block;
          }

          .mega-wrap .lvl-2>li:hover>.lvl-3 {
              /* margin-top: -110px; */
              display: block;
          }
      </style>
      <!-- header start -->
      <header class="sticky-header border-btm-black header-1">
          <div class="header-bottom">
              <div class="container">
                  <div class="row align-items-center">
                      <div class="col-lg-3 col-md-4 col-4">
                          <div class="header-logo mt-1">
                              <a href="{{ route('home') }}" class="logo-main">
                                  <img src="{{ asset('index') }}/assets/img/logo-right.png" loading="lazy"
                                      alt="Learning Alliance"
                                      style="max-width: 250px; max-height: 80px;margin-top: 5px;" />
                              </a>
                          </div>
                      </div>
                      <div class="col-lg-6 d-lg-block d-none">
                          <nav class="site-navigation">
                              <ul class="main-menu list-unstyled justify-content-center">
                                  <li class="menu-list-item nav-item has-dropdown active">
                                      <div class="mega-menu-header">
                                          <a class="nav-link" href="{{ route('home') }}"> Home </a>
                                      </div>
                                  </li>
                                  @php use Illuminate\Support\Str; @endphp

                                  @foreach ($navCategories as $cat)
                                      @php $catHas = $cat->children->isNotEmpty(); @endphp
                                      <li class="menu-list-item nav-item has-dropdown mega">
                                          <div class="mega-menu-header">
                                              <a class="nav-link"
                                                  href="{{ route('category.show', [$cat->id, Str::slug($cat->name)]) }}">
                                                  {{ $cat->name }}
                                              </a>

                                          </div>

                                          @if ($catHas)
                                              <div class="submenu-transform submenu-transform-desktop mega-wrap">
                                                  <!-- Column 1: children -->
                                                  <ul class="submenu list-unstyled mega-col root-col">
                                                      @foreach ($cat->children as $child)
                                                          @php $childHas = $child->children->isNotEmpty(); @endphp
                                                          <li
                                                              class="menu-list-item nav-item-sub {{ $childHas ? 'has-flyout' : '' }}">
                                                              <a class="nav-link-sub nav-text-sub"
                                                                  href="{{ route('category.show', [$child->id, Str::slug($child->name)]) }}">
                                                                  {{ $child->name }}
                                                              </a>



                                                              @if ($childHas)
                                                                  <!-- Column 2: grandchildren -->
                                                                  <ul
                                                                      class="submenu list-unstyled mega-col flyout lvl-2">
                                                                      @foreach ($child->children as $gchild)
                                                                          @php $gchildHas = $gchild->children->isNotEmpty(); @endphp
                                                                          <li
                                                                              class="menu-list-item nav-item-sub {{ $gchildHas ? 'has-flyout' : '' }}">
                                                                              <a class="nav-link-sub nav-text-sub"
                                                                                  href="{{ route('category.show', [$gchild->id, Str::slug($gchild->name)]) }}">
                                                                                  {{ $gchild->name }}
                                                                              </a>


                                                                              @if ($gchildHas)
                                                                                  <!-- Column 3: great-grandchildren -->
                                                                                  <ul
                                                                                      class="submenu list-unstyled mega-col flyout lvl-3">
                                                                                      @foreach ($gchild->children as $gg)
                                                                                          <li
                                                                                              class="menu-list-item nav-item-sub">
                                                                                              <a class="nav-link-sub nav-text-sub"
                                                                                                  href="{{ route('category.show', [$gg->id, Str::slug($gg->name)]) }}">
                                                                                                  {{ $gg->name }}
                                                                                              </a>


                                                                                          </li>
                                                                                      @endforeach
                                                                                  </ul>
                                                                              @endif
                                                                          </li>
                                                                      @endforeach
                                                                  </ul>
                                                              @endif
                                                          </li>
                                                      @endforeach
                                                  </ul>
                                              </div>
                                          @endif
                                      </li>
                                  @endforeach




                                  <li class="menu-list-item nav-item has-dropdown">
                                      <div class="mega-menu-header">
                                          <a class="nav-link" href="{{ route('accessories') }}"> Accessories </a>
                                      </div>
                                  </li>
                                  <li class="menu-list-item nav-item has-dropdown">
                                      <div class="mega-menu-header">
                                          <a class="nav-link sizeguide" href="{{ route('SizeChart') }}">Size
                                              Guide</a>
                                      </div>
                                  </li>
                                  <li class="menu-list-item nav-item has-dropdown">
                                      <div class="mega-menu-header">
                                          <a class="nav-link" href="{{ route('washingInstructions') }}"> Washing
                                              Instructions </a>
                                      </div>
                                  </li>
                                  <li class="menu-list-item nav-item has-dropdown">
                                      <div class="mega-menu-header">
                                          <a class="nav-link sizeguide" href="{{ route('contactus') }}">Contact Us</a>
                                      </div>
                                  </li>
                              </ul>
                          </nav>
                      </div>
                      <div class="col-lg-3 col-md-8 col-8">
                          <div class="header-action d-flex align-items-center justify-content-end">

                              @php
                                  $count = (int) ($cartCount ?? 0);
                                  $badgeText = $count > 99 ? '99+' : $count;
                              @endphp

                              <a class="header-action-item header-cart ms-4 position-relative"
                                  href="{{ route('cartdetails') }}"
                                  aria-label="Cart: {{ $count }} item{{ $count === 1 ? '' : 's' }}">

                                  <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26"
                                      fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                          fill="white" />
                                  </svg>

                                  @if ($count > 0)
                                      <span class="cart-badge"
                                          data-count="{{ $count }}">{{ $badgeText }}</span>
                                  @endif
                              </a>


                              <a class="header-action-item text-black header-hamburger ms-4 d-lg-none"
                                  href="#drawer-menu" data-bs-toggle="offcanvas">
                                  <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="24"
                                      height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round">
                                      <line x1="3" y1="12" x2="21" y2="12"></line>
                                      <line x1="3" y1="6" x2="21" y2="6"></line>
                                      <line x1="3" y1="18" x2="21" y2="18"></line>
                                  </svg>
                              </a>

                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </header>
      <!-- header end -->
      <!-- drawer menu start -->
      <div class="offcanvas offcanvas-start d-flex d-lg-none" tabindex="-1" id="drawer-menu">
          <div class="offcanvas-wrapper">
              <div class="offcanvas-header border-btm-black">
                  <a href="{{ route('home') }}" class="logo-main">
                      <img src="{{ asset('index') }}/assets/img/logo-right.png" loading="lazy" alt="Learning Alliance"
                          style="max-width: 200px; max-height: 60px;" />
                  </a>
                  <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" style="margin-left: 17rem;">
                      <i class="fas fa-times" style="font-size: 24px;"></i>
                  </button>
              </div>

              <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
                  <nav class="site-navigation">
                      <ul class="main-menu list-unstyled">
                          <!-- Static Pages like Home -->
                          <li class="menu-list-item nav-item">
                              <a class="nav-link" href="{{ route('home') }}">Home</a>
                          </li>
                          <!-- Loop through dynamic categories (Boys, Girls) -->
                          @foreach ($navCategories as $cat)
                              <li class="menu-list-item nav-item has-dropdown">
                                  <div class="mega-menu-header">
                                      <a class="nav-link"
                                          href="{{ route('category.show', [$cat->id, Str::slug($cat->name)]) }}">
                                          {{ $cat->name }}
                                      </a>
                                  </div>

                                  @if ($cat->children->isNotEmpty())
                                      <div class="submenu-transform submenu-transform-desktop mega-wrap">
                                          <ul class="submenu list-unstyled mega-col root-col">
                                              @foreach ($cat->children as $child)
                                                  <li class="menu-list-item nav-item-sub">
                                                      <a class="nav-link-sub nav-text-sub"
                                                          href="{{ route('category.show', [$child->id, Str::slug($child->name)]) }}">
                                                          {{ $child->name }}
                                                      </a>
                                                  </li>
                                              @endforeach
                                          </ul>
                                      </div>
                                  @endif
                              </li>
                          @endforeach

                          <li class="menu-list-item nav-item">
                              <a class="nav-link" href="{{ route('accessories') }}">Accessories</a>
                          </li>
                          <li class="menu-list-item nav-item">
                              <a class="nav-link" href="{{ route('SizeChart') }}">Size Guide</a>
                          </li>
                          <li class="menu-list-item nav-item">
                              <a class="nav-link" href="{{ route('washingInstructions') }}">Washing Instructions</a>
                          </li>
                          <li class="menu-list-item nav-item">
                              <a class="nav-link" href="{{ route('contactus') }}">Contact</a>
                          </li>


                      </ul>
                  </nav>

                  <!-- Utility Menu (Phone, Login, Wishlist, etc.) -->
                  <ul class="utility-menu list-unstyled">
                      <li class="utilty-menu-item"><a href="mailto:info@learningalliance.edu.pk"
                              class="contact-text"><b>Email:</b>
                              info@learningalliance.edu.pk</a>
                      </li>
                      <li class="utilty-menu-item"><a href="tel:+9242111666633" class="contact-text"><b>DHA:</b>
                              +92-42-111-66-66-33</a>
                      </li>
                      <li class="utilty-menu-item"><a href="tel:+9242111666611" class="contact-text"><b>Aziz
                                  Avenue:</b>
                              +92-42-111-66-66-11</a></li>
                      <li class="utilty-menu-item"> <a href="tel:+9241111666633"
                              class="contact-text"><b>Faisalabad:</b>
                              +92-41-111-66-66-33</a></li>
                  </ul>
              </div>
          </div>
      </div>
