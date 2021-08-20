<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                상세보기
            </h2>
        </template>
        <div class="p-4 bg-gray-50 dark:bg-gray-900 flex items-center justify-center w-screen lg:px-24">
            <div class="px-2 py-4 bg-white dark:bg-gray-800 shadow rounded-lg max-w-lg  min-w-full">
                <div class="flex mb-4">
                    <img class="w-12 h-12 rounded-full" v-bind:src="posts.profile_photo_path" />
                    <div class="ml-2 mt-0.5">
                        <span
                            class="block font-medium text-base leading-snug text-black dark:text-gray-100">{{ posts.name }}</span>
                        <span
                            class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">{{ posts.date }}</span>
                    </div>
                </div>
                <p class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal">{{ posts.content }}</p>
                <div class="flex justify-between items-center">
                    <div class="flex ">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" class="p-0.5 h-6 "
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.913 511.913"
                            style="enable-background:new 0 0 511.913 511.913;" xml:space="preserve">
                            <g>
                                <path style="fill:#19CFFC;" d="M121,98.5H45c-8.291,0-15-6.709-15-15s6.709-15,15-15h76c8.291,0,15,6.709,15,15
		S129.291,98.5,121,98.5z" />
                                <path style="fill:#19CFFC;" d="M121,218.5H45c-8.291,0-15-6.709-15-15s6.709-15,15-15h76c8.291,0,15,6.709,15,15
		S129.291,218.5,121,218.5z" />
                                <path style="fill:#19CFFC;" d="M91,158.5H15c-8.291,0-15-6.709-15-15s6.709-15,15-15h76c8.291,0,15,6.709,15,15
		S99.291,158.5,91,158.5z" />
                            </g>
                            <g>
                                <path style="fill:#2864F0;"
                                    d="M280.141,51.039c-11.924-7.925-27.744-6.357-37.852,3.75l-82.5,82.5
		c-11.719,11.719-11.719,30.703,0,42.422s30.703,11.719,42.422,0l65.112-65.112l46.593,33.563l63.724-29.623L280.141,51.039z" />
                                <path style="fill:#2864F0;" d="M219.789,242.289l-195,195c-11.719,11.719-11.719,30.703,0,42.422
		c11.718,11.718,30.703,11.719,42.422,0L241,305.922L219.789,242.289z" />
                            </g>
                            <g>
                                <path style="fill:#3389FF;" d="M482,203.5h-91v-60c0-11.793-7.134-22.955-18.536-27.684c-11.48-4.748-24.401-1.812-32.684,6.482
		L219.789,242.289c-11.719,11.719-11.719,30.703,0,42.422l68.789,68.789l-83.789,83.789c-11.719,11.719-11.719,30.703,0,42.422
		c11.718,11.718,30.703,11.719,42.422,0l105-105c11.719-11.719,11.719-30.703,0-42.422L283.422,263.5L331,215.922V233.5
		c0,16.567,13.433,30,30,30h121c16.567,0,30-13.433,30-30S498.567,203.5,482,203.5z" />
                                <circle style="fill:#3389FF;" cx="436" cy="68.5" r="45" />
                            </g>

                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-gray-400  font-light">{{ posts.run }}</span>
                    </div>
                    <div class="ml-1 btn-group">
                        <button v-if="posts.flag==0" class="btn btn-sm btn-success" @click="approve">승인</button>
                        <button v-else class="btn btn-sm btn-error" @click="approve">미승인</button>
                        <button class="btn btn-sm" @click="moveList">목록</button>
                        <button class="btn btn-sm btn-info" @click="editpost">수정</button>
                        <button class="btn btn-sm btn-error" @click="del">삭제</button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import axios from 'axios'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    export default {
        components: {
            AppLayout,
            LoadingBar
        },
        data: () => ({
            posts: [],
            pageId: '',
            postId: '',
            ifLoading: 0,
        }),
        computed: {

        },
        mounted() {
            var {
                pathname
            } = window.location
            var splitPathname = pathname.split("/")
            const postId = splitPathname[splitPathname.length - 1]
            const pageId = window.location.href.charAt(window.location.href.length-1)
            console.log(postId);
            console.log(pageId);
            this.pageId = pageId;
            this.postId = postId;
            axios.get("/api/attend_posts/" + postId)
                .then(response => {
                    this.posts = response.data.post[0]
                })
        },
        methods: {
            approve() {
                let value = {
                    user_id: this.posts.user_id,
                    run: this.posts.run,
                    req_id: document.head.querySelector('meta[name="user-id"]').content,
                }
                axios.patch("/api/run/" + this.posts.id, null, {
                        params: value
                    })
                    .then(response => {
                        console.log(response);
                        alert("승인됨")
                        window.location.href = '/runauth?page='+this.pageId;
                    })
            },
            del() {
                let value2 = {
                    user_id: this.posts.user_id
                }
                axios.delete("/api/attend_posts/" + this.posts.id, {
                        params: value2
                    })
                    .then(response => {
                        console.log(response);
                        window.location.href = '/runauth?page='+this.pageId;
                    })
            },
            moveList() {
                window.location.href = '/runauth/?page=' +this.pageId
            },
            editpost() {
                window.location.href = '/edit/' + this.postId +'?page=' + this.pageId
            }
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>