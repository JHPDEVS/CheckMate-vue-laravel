<template>
    <app-layout>
        <div class="md:px-32 w-full">
            <div>
                <my-post-info />
                <div
                    class="relative flex items-center self-center min-w-full max-w-xl p-4 overflow-hidden text-gray-600 focus-within:text-gray-400">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6">
                        <button v-on:click="search"
                            class="p-1 focus:outline-none focus:shadow-none hover:text-blue-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </span>
                    <input type="search" @keyup.enter="search" v-model="searchQuery"
                        class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 border border-transparent appearance-none rounded-tg placeholder-gray-400 focus:bg-white focus:outline-none focus:border-blue-500 focus:text-gray-900 focus:shadow-outline-blue"
                        style="border-radius: 25px, white-space: pre" placeholder="제목 이름으로 검색" autocomplete="off">


                </div>


            </div>
            <div class="bg-white px-4 py-3   items-center justify-between border-t border-gray-200 sm:px-6 lg:flex">
                <div class="flex-1 flex items-center justify-between ">
                    <div>
                        <nav class="relative z-0 inline-flex flex-nowrap rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                            <a v-for="(item,i) in pageLinks" v-bind:key="i">
                                <button @click="refreshPage(item.label)" v-if="item.active==true"
                                    class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ item.label }}</button>
                                <button @click="refreshPage(item.label)" v-else
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-mediums">{{ item.label }}</button>
                            </a>

                        </nav>

                    </div>

                </div>
                <div>
                </div>
            </div>
            <div class="lg:flex ">
                <div v-if="total == 0">
                    <div class="alert alert-error">
                        <div class="flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="w-6 h-6 mx-2 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <label>검색된 게시글이 없습니다</label>
                        </div>
                    </div>
                </div>
                <table v-else class="table-compact  min-w-full border-collapse">
                    <thead class="text-center bg-gray-100 font-sans">
                        <tr class="hover:bg-grey-lighter">
                            <th></th>
                            <th>제목</th>
                            <th>작성자</th>
                            <th>시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(post,i) in posts" v-bind:key=i
                            class="text-center justify-center border-b border-black-200 hover:bg-gray-100">
                            <th>{{post.id}}</th>
                            <th><a v-bind:href="`/freeshow/${post.id}?page=${page.current_page}`">{{ post.title }}
                            <span v-if="post.comments_count"
                                        class="text-error">[{{ post.comments_count }}] </span></a></th>
                            <th>{{ post.name }}</th>
                            <th>{{ post.updated_at }}</th>
                        </tr>
                    </tbody>
                </table>

            </div>
                <div class=" bottom-0 mx-auto">
  <a class=" fixed left-1/2 transform -translate-x-2/4 bottom-0 bg-indigo-500 text-white p-2 rounded hover:bg-indigo-700 m-2 " v-bind:href="`/freewrite?page=${page.current_page}`" ><i class="fas fa-pen font-xl">글쓰기</i></a>
</div>
        </div>
    </app-layout>
</template>

<script>
    import Dialog from '@/Jetstream/DialogModal'
    import BadgeRed from '@/Geofencing/BadgeRed'
    import BadgeGreen from '@/Geofencing/BadgeGreen'
    import BadgeYellow from '@/Geofencing/BadgeYellow'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    import AppLayout from '@/Layouts/AppLayout'
    import myPostInfo from '@/Pages/Board/MyPostInfo'
    import weekRunStatus from './weekRunStatus.vue'
    export default {
        data: function () {
            return {
                header: '',
                header2: '',
                msg: '',
                user_name: document.head.querySelector('meta[name="user-name"]').content,
                user_sid: document.head.querySelector('meta[name="user-sid"]').content,
                user_class: document.head.querySelector('meta[name="user-class"]').content,
                user_photo: document.head.querySelector('meta[name="user-photo"]').content,
                user_id: document.head.querySelector('meta[name="user-id"]').content,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                dialogShow: false,
                page: [],
                pageLinks: [],
                pageId: '',
                posts: [],
                isLoading: 0,
                showAttend: false,
                searchQuery: '',
                total: 1,
            }
        },
        components: {
            AppLayout,
            Dialog,
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
            LoadingBar,
            weekRunStatus,
            myPostInfo

        },
        mounted() {
            var {
                pathname
            } = window.location
            var splitPathname = pathname.split("/")
            const postId = splitPathname[splitPathname.length - 1]
            var pageId = parseInt(window.location.href.charAt(window.location.href.length - 1))
            console.log(postId);
            console.log(pageId);
            if (Number.isInteger(pageId)) {
                console.log("현재 페이지")
                this.pageId = pageId;
            } else {
                console.log("첫페이지로")
            }
            axios.get('/api/posts/index?page=' + this.pageId)
                .then(response => {
                    this.page = response.data.posts;
                    this.pageId = response.data.posts.current_page
                    this.posts = response.data.posts.data;
                    this.isLoading = 1;
                    this.pageLinks = response.data.posts.links
                    this.total = response.data.posts.total
                    console.log(this.pageId);
                    console.log(this.pageLinks)
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {
            refreshPage(page) {
                if (page == "<") {
                    if (this.pageLinks[0].url) {
                        page = this.pageLinks[0].url.charAt(this.pageLinks[0].url.length - 1)
                    } else {
                        page = 1
                    }
                }
                if (page == ">") {
                    if (this.pageLinks[this.page.last_page + 1].url) {
                        page = this.pageLinks[this.page.last_page + 1].url.charAt(this.pageLinks[this.page.last_page +
                            1].url.length - 1)
                    } else {
                        page = this.page.last_page;
                    }
                }
                if (this.searchQuery) {
                    axios.get('/api/posts/search/' + this.searchQuery + '?page=' + page)
                        .then(response => {
                            this.page = response.data.posts;
                            this.pageId = response.data.posts.current_page
                            this.posts = response.data.posts.data;
                            this.isLoading = 1;
                            this.pageLinks = response.data.posts.links
                            this.total = response.data.posts.total
                        })
                } else {
                    axios.get("/api/posts/index?page=" + page)
                        .then(response => {
                            this.page = response.data.posts;
                            this.pageId = response.data.posts.current_page
                            this.posts = response.data.posts.data;
                            this.isLoading = 1;
                            this.pageLinks = response.data.posts.links
                            this.total = response.data.posts.total
                        })
                }
            },
            search() {
                axios.get('/api/posts/search/' + this.searchQuery)
                    .then(response => {
                        this.page = response.data.posts;
                        this.pageId = response.data.posts.current_page
                        this.posts = response.data.posts.data;
                        this.isLoading = 1;
                        this.pageLinks = response.data.posts.links
                        this.total = response.data.posts.total
                    })
                    .catch(response => {
                        if (!this.searchQuery) {
                            axios.get('/api/posts/index?page=' + this.pageId)
                                .then(response => {
                                    this.page = response.data.posts;
                                    this.pageId = response.data.posts.current_page
                                    this.posts = response.data.posts.data;
                                    this.isLoading = 1;
                                    this.pageLinks = response.data.posts.links
                                    this.total = response.data.posts.total
                                })
                        }
                    })
            }
        }
    }
</script>
<style>
    jet-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>