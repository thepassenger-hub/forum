<aside class="menu column is-3">
    <p class="menu-label">
        Channels
    </p>
    <ul class="menu-list">
        <router-link v-for="channel in channels" tag="li" :to="{ name: 'channel', params: { channel: channel.slug}}" 
            :key="channel.slug"><a>@{{channel.name}}</a></router-link>
    </ul>
    <br>
    <br>
    <p class="menu-label">
        Filters
    </p>
    <ul class="menu-list">
        {{-- <router-link v-for="channel in channels" tag="li" :to="{ name: 'channel', params: { channel: channel.slug}}" 
            :key="channel.slug"><a>@{{channel.name}}</a></router-link> --}}
        <router-link tag="li" to="/" exact>
            <a>All Threads</a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { by: 'forumAdmin' }}" exact>
            <a>My Threads</a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { contributed_to: 1 }}" exact>
            <a>My Partecipation</a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { trending: 1 }}" exact>
            <a>Popular this week</a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { popular: 1 }}" exact>
            <a>Popular All Time</a>
        </router-link>
    </ul>
</aside>

