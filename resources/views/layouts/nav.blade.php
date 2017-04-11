<nav class="nav">
  <div class="nav-left">
    <a class="nav-item">
      <p>Logo qua</p>
    </a>
    <router-link class="nav-item is-tab" to='/' exact>
      <p>Home</p>
    </router-link>
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
  @if (Route::has('login'))
      <div class="nav-right nav-menu">
        @if (Auth::check())
          <router-link class="nav-item" to='/logout' >
            <p>Logout</p>
          </router-link>
        @else
          <router-link class="nav-item" to='/login'>
            <p>Login</p>
          </router-link>
          <router-link class="nav-item" to='/register'>
            <p>Register</p>
          </router-link>
        @endif
    </div>
  @endif
</nav>