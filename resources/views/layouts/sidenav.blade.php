<aside class="menu column is-3">
    <router-link class="button" v-if="user" to="new-thread">Create new Thread</router-link>

     <p class="menu-label">
        Filters
    </p>
    <ul class="menu-list">
        <router-link tag="li" to="/" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-globe"></i>
                </span>All Threads
            </a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { by: 'forumAdmin' }}" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-user"></i>
                </span>My Threads
            </a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { contributed_to: 1 }}" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-code-fork"></i>
                </span>My Partecipation
            </a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { trending: 1 }}" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-users"></i>
                </span>Popular this week
            </a>
        </router-link>
        <router-link tag="li" :to="{ path: '/threads', query: { popular: 1 }}" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-users"></i>
                </span>Popular All Time
            </a>
        </router-link>
    </ul>
    <br>
    <br>
    <p class="menu-label">
        Channels
    </p>
    <ul class="menu-list">
        <router-link v-for="channel in channels" tag="li" :to="{ name: 'channel', params: { channel: channel.slug}}" 
            :key="channel.slug"><a>@{{channel.name}}</a></router-link>
    </ul>
</aside>

