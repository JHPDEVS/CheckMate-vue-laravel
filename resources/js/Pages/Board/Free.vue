<template>
    <app-layout>
        <template #header>
            <div class="tabs">
                <a class="tab  tab-lifted tab-lg" :href="route('notice')">공지사항</a>
                <a class="tab  tab-lifted tab-lg tab-active" :href="route('free')">자유게시판</a>
            </div>
        </template>
        <div class="md:px-32 py-4 w-full">
            <div class="">
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
                        style="border-radius: 25px, white-space: pre" placeholder="제목으로 검색" autocomplete="off">
                </div>
            </div>

            <div class="lg:flex ">
                <div v-if="!(total == 0)" class="btn-group">
                    <a class="btn btn-secondary btn-outline text-lg fas fa-pen"
                        v-bind:href="`/freewrite?page=${page.current_page}`"></a>
                    <span v-for="(item) in pageLinks" v-bind:key="item" class="mx-auto">
                        <a v-if="item.active ==true && typeof(+item.label == 'number')"
                            v-bind:href="`/freeshow/?page=${item.label}`" class="btn btn-success">{{ item.label }}</a>
                        <a v-else v-bind:href="`/freeshow/?page=${item.label}`" class="btn">{{ item.label }}</a>
                    </span>
                </div>
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
                <table v-else class="table-compact min-w-full border-collapse pt-3">
                    <thead class="text-center bg-gray-100 font-sans">
                        <tr class="hover:bg-grey-lighter">
                            <th></th>
                            <th>제목</th>
                            <th>시간</th>
                            <th>작성자</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(post,i) in posts" v-bind:key=i
                            class="text-center justify-center border-b border-black-200 hover:bg-gray-100">
                            <th>{{post.id}}</th>
                            <th><a v-bind:href="`/freeshow/${post.id}?page=${page.current_page}`">{{ post.title }} <span v-if="post.comments_count" class="text-error">[{{ post.comments_count }}]</span></a></th>
                            <th>{{ post.updated_at }}</th>
                            <th>{{ post.name }}</th>
                        </tr>
                    </tbody>
                </table>

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
    import myAttendStatus from '@/Pages/Dashboard/MyAttendStatus'
    import weekRunStatus from './weekRunStatus.vue'
    export default {
        data: function () {
            return {
                header: '',
                header2: '',
                msg: '',
               
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
            myAttendStatus

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
                    this.pageLinks.splice(0, 1) // 이전 삭제
                    this.pageLinks.splice(this.pageLinks.length - 1, 1) // 다음 삭제
                    this.total = response.data.posts.total
                    console.log(this.pageId);
                    console.log(this.pageLinks)
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {
            search() {
                axios.get('/api/posts/search/' + this.searchQuery)
                    .then(response => {
                        this.posts = response.data.posts.data;
                        this.pageLinks = response.data.posts.links
                        this.pageLinks.splice(0, 1) // 이전 삭제
                        this.pageLinks.splice(this.pageLinks.length - 1, 1) // 다음 삭제
                        this.total = response.data.posts.total
                        console.log(this.posts)
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
                                    this.pageLinks.splice(0, 1) // 이전 삭제
                                    this.pageLinks.splice(this.pageLinks.length - 1, 1) // 다음 삭제
                                    this.total = response.data.posts.total;
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