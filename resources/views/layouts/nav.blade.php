<nav class="nav">
  <div class="nav-left">
    <a class="nav-item">
      <p>Logo qua</p>
    </a>
    <div class="nav-item field has-addons" id="search-bar">
      <p class="control">
          <input class="input" v-model="searchQuery" placeholder="Search">
      </p>
      <p class="control">
        <router-link :to="{ path: '/threads', query: { search: searchQuery }}" exact>
            <button class="button is-primary">
                SEARCH
            </button>
        </router-link>
          {{-- <button class="button is-primary" @click="">SEARCH</button> --}}
      </p>
    </div>
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
      <router-link v-if="username" class="nav-item" :to="'/@'+username">
        <p class="image is-64x64" id="nav-avatar">
            <img :src="user.profile.avatar">
        </p>
        <p>@{{username}}</p>
      </router-link>
      <a v-if="username" class="nav-item" @click="logout">
        <p>Logout</p>
      </a>
      <router-link v-if="! username" class="nav-item" to='/sign-in'>
        <p>Login/Register</p>
      </router-link>

  </div>
</nav>