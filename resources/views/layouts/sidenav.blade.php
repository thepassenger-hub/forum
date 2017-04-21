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
        <router-link v-for="channel in channels" tag="li" :to="{ name: 'channel', params: { channel: channel.slug}}" 
            :key="channel.slug"><a>@{{channel.name}}</a></router-link>
    </ul>
</aside>

