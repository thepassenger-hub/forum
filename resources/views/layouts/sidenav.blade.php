<aside class="menu column is-3">
    <keep-alive>
        <router-link class="button" id="new-thread-button" v-if="user" :to="{path: '/new-thread'}">Create new Thread</router-link>
    </keep-alive>

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
        <router-link v-if="$root.username" tag="li" :to="{ path: '/threads', query: { by: $root.username }}" exact>
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-user"></i>
                </span>My Threads
            </a>
        </router-link>
        <router-link v-if="$root.username" tag="li" :to="{ path: '/threads', query: { contributed_to: 1 }}" exact>
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
            :key="channel.slug">
            <a>
                <span class="icon is-small filter-icons">
                    <i class="fa fa-asterisk"></i>
                </span>@{{channel.name}}
            </a>
            </router-link>
    </ul>
</aside>

