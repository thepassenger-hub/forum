<template>
    <nav class="pagination" v-if="posts">
        <a class="pagination-previous" :class="{'is-disabled': this.current == 1}" 
            @click="changePage(current - 1)">Previous</a>
        <a class="pagination-next" :class="{'is-disabled': this.current == this.pageTabs.slice(-1)[0]}"
            @click="changePage(current + 1)">Next</a>
        <ul class="pagination-list" v-if="pageTabs.length <= 9">
            <li v-for="page in pageTabs" @click="changePage(page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
        </ul>
        <ul class="pagination-list" v-else>
            <li v-for="page in startPages" @click="changePage(page)">
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
            <li v-if="startEllipsis" @click="changePage(middlePages[0] !== undefined ? middlePages[0] - 1 : endPages[0] - 1)">
                <a class="pagination-link">...</a>
            </li>
            <li v-for="page in middlePages" @click="changePage(page)">
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
            <li v-if="endEllipsis" 
                @click="changePage(middlePages.slice(-1)[0] !== undefined ? middlePages.slice(-1)[0] + 1 : startPages.slice(-1)[0] + 1)">
                <a class="pagination-link">...</a>
            </li>
            <li v-for="page in endPages" @click="changePage(page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
        </ul>
        
        
    </nav>
</template>

<script>
    export default {
        props: ['posts', 'perPage', 'current'],
        data() {
            return {
                pageTabs: [],
                startPages: [],
                startEllipsis: false,
                middlePages: [],
                endEllipsis: false,
                endPages: []
            }
        },
        created() {
            this.getPageTabs();
        },
        watch: {
            posts(){
                this.getPageTabs();
            },
            current(){
                this.setPageTabs();
            }
        },
        methods: {
            getPageTabs() {
                let numOfPages = Math.ceil(this.posts.length / this.perPage);
                this.pageTabs = Array(numOfPages).fill().map((e,i)=>i+1); // From n pages creates [1,2,...,n] 
                this.setPageTabs();
            },

            setPageTabs(){
                if (3 < this.current &&  this.current < this.pageTabs.length -2 ) {
                    this.startPages = this.pageTabs.slice(0,1);
                    this.startEllipsis = true;
                    this.middlePages = this.pageTabs.slice(this.current - 3, this.current + 2);
                    this.endEllipsis = true;
                    this.endPages = this.pageTabs.slice(-1);
                }
                else if (this.current > 3) {
                    this.startPages = this.pageTabs.slice(0,1);
                    this.startEllipsis = true;
                    this.middlePages = [];    
                    this.endEllipsis = false;
                    this.endPages = this.pageTabs.slice(-6);
                }
                else {
                    this.startPages = this.pageTabs.slice(0, 6);
                    this.startEllipsis = false;
                    this.middlePages = [];
                    this.endEllipsis = true;
                    this.endPages = this.pageTabs.slice(-1);
                }
            },

            changePage(page) {
                this.$emit('pageClicked', page); 
            }

        }
    }
</script>