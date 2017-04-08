<nav class="nav">
  <div class="nav-left">
    <a class="nav-item">
      <p>Logo qua</p>
    </a>
  </div>

  <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
  <!-- You need JavaScript to toggle the "is-active" class on "nav-menu" -->
  <span class="nav-toggle">
    <span></span>
    <span></span>
    <span></span>
  </span>

  <!-- This "nav-menu" is hidden on mobile -->
  <!-- Add the modifier "is-active" to display it on mobile -->
  <div class="nav-right nav-menu">
    @if (Route::has('login'))
        @if (Auth::check())
              <router-link to='/' exact>
                <a class="nav-item">Home</a>
              </router-link>
        @else
              <router-link to='/login'>
                <a class="nav-item">Login</a>
              </router-link>
              <router-link to='/register'>
                <a class="nav-item">Register</a>
              </router-link>
        @endif
      @endif

  </div>
</nav>