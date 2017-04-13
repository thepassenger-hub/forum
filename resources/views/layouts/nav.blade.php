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
  <div class="nav-right nav-menu">
      <a v-if="username" class="nav-item">
        <p>@{{username}}</p>
      </a>
      <a v-if="username" class="nav-item" @click="logout">
        <p>Logout</p>
      </a>
      <router-link v-if="! username" class="nav-item" to='/sign-in'>
        <p>Login/Register</p>
      </router-link>

  </div>
</nav>