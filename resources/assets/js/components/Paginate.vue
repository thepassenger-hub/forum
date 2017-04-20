<template>
    <nav class="pagination" v-if="threads">
        <a class="pagination-previous" :class="{'is-disabled': this.current == 1}" @click="$emit('prev')">Previous</a>
        <a class="pagination-next" :class="{'is-disabled': this.current == this.pageTabs.slice(-1)[0]}" @click="$emit('next')">Next page</a>
        <ul class="pagination-list" v-if="pageTabs.length <= 9">
            <li v-for="page in pageTabs" @click="$emit('pageClicked', page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
        </ul>
        <ul class="pagination-list" v-else>
            <li v-for="page in startPages" @click="$emit('pageClicked', page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
            <li v-if="startEllipsis" @click="$emit('pageClicked', middlePages[0] !== undefined ? middlePages[0] - 1 : endPages[0] - 1)">
                <a class="pagination-link">...</a>
            </li>
            <li v-for="page in middlePages" @click="$emit('pageClicked', page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
            <li v-if="endEllipsis" @click="$emit('pageClicked', 
                middlePages.slice(-1)[0] !== undefined ? middlePages.slice(-1)[0] + 1 : startPages.slice(-1)[0] + 1)">
                <a class="pagination-link">...</a>
            </li>
            <li v-for="page in endPages" @click="$emit('pageClicked', page)" >
                <a class="pagination-link" :class="{'is-current': current === page}">{{page}}</a>
            </li>
        </ul>
        
        
    </nav>
</template>

<script>
    export default {
        props: ['threads', 'perPage', 'current'],
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
        watch: {
            threads(){
                this.getPageTabs();
            },
            current(){
                this.setPageTabs();
            }
        },
        methods: {
            getPageTabs() {
                let numOfPages = Math.ceil(this.threads.length / this.perPage);
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
            }
            // 1,2,3,4,5,6,7             n=7 i=1
            // 1,2,3,4,5,6,...,10        n=10 i=1
            // 1...,3,4,5,6,7,...,10      m=10 i=5
            // 1.........5,6,7,8,9,10     n=10 i=8
            // if 3 < current < n-2 show current-2,current-1,current, current+1,+2
            // else if current > 3 1....n-5,-4,-3,-2,-1,n
            // else 1,2,3,4,5,6......10

        }
    }
</script>