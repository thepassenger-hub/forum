<nav class="nav">
  <div class="nav-left">
    <a class="nav-item">
      <p>Logo qua</p>
    </a>
    <div class="nav-item field has-addons is-hidden-mobile" id="search-bar">
      <p class="control">
          <input class="input" v-model="searchQuery" placeholder="Search">
      </p>
      <p class="control">
        <router-link :to="{ path: '/threads', query: { search: searchQuery }}" exact>
            <button class="button is-primary">
                SEARCH
            </button>
        </router-link>
      </p>
    </div>
  </div>

  <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
  <!-- You need JavaScript to toggle the "is-active" class on "nav-menu" -->
  <span class="nav-toggle" @click="showNavbar = !showNavbar" :class="{'is-active': showNavbar}">
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
<nav class="nav is-hidden-tablet" id="mobile-nav" v-if="showNavbar">
<div>
    <ul class="is-flex-tablet nav-menu">
      <li >
      <div class="field has-addons">
         <p class="control">
          <input class="input" v-model="searchQuery" placeholder="Search">
        </p>
        <p class="control">
          <router-link :to="{ path: '/threads', query: { search: searchQuery }}" exact>
              <button class="button is-primary">
                  SEARCH
              </button>
          </router-link>
        </p>
      </div>
       
      </li>
      <li>
        <router-link v-if="username" class="nav-item" :to="'/@'+username">
          <p>@{{username}}</p>
      </router-link>
      </li>
      <li>
        <a v-if="username" class="nav-item" @click="logout">
          <p>Logout</p>
        </a>
        <router-link v-if="! username" class="nav-item" to='/sign-in'>
          <p>Login/Register</p>
        </router-link>
      </li>
    </ul>
  </div>
</nav>