<template>
    <app-layout>
        <div class="p-2 bg-gray-50 dark:bg-gray-900 flex items-center justify-center w-screen lg:px-24">
            <div class="px-2 py-4 bg-white dark:bg-gray-800 shadow rounded-lg max-w-lg  min-w-full">
                <div class="flex mb-4">
                    <img class="w-12 h-12 object-cover rounded-full shadow mr-2 cursor-pointer"
                        v-bind:src="posts.profile_photo_path" />
                    <div class="ml-2 mt-0.5">
                        <span
                            class="block font-medium text-base leading-snug text-black dark:text-gray-100">{{ posts.name }}
                            <span v-if="posts.flag==1" class="text-success"> 
                                    (<i class="fas fa-running fa-2xl"><span> : {{ posts.run }}</span></i> 승인)</span> <span
                                v-else class="text-error">(<i class="fas fa-running fa-2xl"><span> : {{ posts.run }}</span></i> 미승인)</span> </span>
                        <span
                            class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">{{ posts.date }}</span>
                    </div>
                </div>
                <p class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal">{{ posts.content }}</p>
                <p class="py-1"> <img v-if="posts.image" v-bind:src="`/storage/images/${posts.image}`"
                        v-bind:href="`/storage/images/${posts.image}`" v-bind:alt="posts.image" class="object-cover">
                </p>
                <div class="flex justify-between items-center">
                    <div class="flex ">
                    </div>
                    <div class="ml-1 btn-group">
                        <button
                            v-if="(posts.flag==0 && $page.props.user.current_team_id==4) || (posts.flag==0 && $page.props.user.current_team_id==5)"
                            class="btn btn-sm btn-success" @click="approve">승인</button>
                        <button
                            v-else-if="(posts.flag==1 && $page.props.user.current_team_id==4) || (posts.flag==1 && $page.props.user.current_team_id==5)"
                            class="btn btn-sm btn-error" @click="approve">미승인</button>
                        <button class="btn btn-sm" @click="moveList">목록</button>
                        <button v-if="$page.props.user.id == posts.user_id" class="btn btn-sm btn-info"
                            @click="editpost">수정</button>
                        <button
                            v-if="$page.props.user.id == posts.user_id || $page.props.user.current_team_id==4 || $page.props.user.current_team_id==5"
                            class="btn btn-sm btn-error" @click="opendel">삭제</button>
                    </div>

                </div>
                <div
                    class="relative flex items-center self-center min-w-full max-w-xl p-4 overflow-hidden text-gray-600 focus-within:text-gray-400">
                    <img class='w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer' alt='User avatar'
                        :src="user_photo">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6">
                        <button v-on:click="submit"
                            class="p-1 focus:outline-none focus:shadow-none hover:text-blue-500">
                            <i class="fas fa-comment"></i>
                        </button>
                    </span>
                    <input type="search" @keyup.enter="submit" v-model="content"
                        class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 border border-transparent appearance-none rounded-tg placeholder-gray-400 focus:bg-white focus:outline-none focus:border-blue-500 focus:text-gray-900 focus:shadow-outline-blue"
                        style="border-radius: 25px, white-space: pre" placeholder="댓글을 남겨보세요" autocomplete="off">
                </div>
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div v-if="commentLinks.total!=0"
                    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex items-center justify-between flex-nowrap">
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                                <a v-for="(commentlink,i) in commentLinks.links" v-bind:key="i">
                                    <button @click="refreshPage(commentlink.label)" v-if="commentlink.active==true"
                                        class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ commentlink.label }}</button>
                                    <button @click="refreshPage(commentlink.label)" v-else
                                        class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-mediums">{{ commentlink.label }}</button>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-start bg-white dark:bg-gray-800 min-w-full"
                    v-for="(comment) in comments" v-bind:key="comment.id" style="word-break: break-word;">
                    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-200 p-4 antialiased flex max-w-lg">
                        <img class="w-10 h-10 object-cover rounded-full shadow mr-2 cursor-pointer" :src="comment.profile_photo_path" />
                        <div v-if="!(commentID == comment.id)">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                                <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                                <div class="text-normal leading-snug md:leading-normal resize">{{ comment.content }}
                                </div>

                            </div>
                            <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-edit fa-xl text-success hover:text-green-300" 
                                    v-on:click="editLayout(comment.id)"></i>
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-trash-alt fa-xl px-2 text-error hover:text-red-300"
                                    v-on:click="deleteComment(comment.id)"></i>
                            </div>
                        </div>
                        <div v-else>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5  max-w-full">
                                <div class="font-semibold text-sm leading-relaxed">{{ comment.name }}</div>
                                <textarea class="text-normal leading-snug md:leading-normal resize"
                                    v-model="comment.content" cols="17" rows="5"></textarea>
                            </div>
                            <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">{{ comment.updated_at }}
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-xl fa-window-close text-error hover:text-red-300"
                                    v-on:click="closeEditComment"></i>
                                <i v-if="$page.props.user.id == comment.user_id" class="fas fa-check fa-xl px-2 text-success hover:text-green-300"
                                    v-on:click="editComment(comment.id,comment.content)"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </app-layout>
    <Dialog :show="dialog" @close="closeDialog">

        <template #title>
            {{ msg }}
        </template>

        <template #content>
            {{header}}
            <br>
            {{header2}}
        </template>

        <template #footer>
            <jet-button type="button"
                class="ml-2 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                @click="del">
                삭제
            </jet-button>
            <jet-button type="button"
                class="ml-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                @click="closeDialog">
                닫기
            </jet-button>

        </template>
    </Dialog>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Dialog from '@/Jetstream/DialogModal'
    import axios from 'axios'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";
    export default {
        components: {
            AppLayout,
            LoadingBar,
            Dialog,
            LottieAnimation
        },
        data: () => ({
            msg: '',
            header: '',
            posts: [],
            pageId: '',
            postId: '',
            contnet: '',
            commentID: '',
            comments: [],
            saveComment: [],
            dialog: false,
            commentPage: 1,
            commentLinks: [],
            user_photo: document.head.querySelector('meta[name="user-photo"]').content,
            user_id: document.head.querySelector('meta[name="user-id"]').content,
        }),
        computed: {

        },
        mounted() {
            var {
                pathname
            } = window.location
            var splitPathname = pathname.split("/")
            const postId = splitPathname[splitPathname.length - 1]
            const pageId = window.location.href.charAt(window.location.href.length - 1)
            console.log(postId);
            console.log(pageId);
            this.pageId = pageId;
            this.postId = postId;
            axios.get("/api/attend_posts/" + postId)
                .then(response => {
                    this.posts = response.data.post[0]
                    this.comments = response.data.comment.data
                    this.commentLinks = response.data.comment
                    this.commentPage = response.data.comment.current_page
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
                        window.location.href = '/runauth/show/' + this.postId + '?page=' + this.pageId;
                    })
            },
            opendel() {
                this.msg = "삭제"
                this.header = "삭제 하시겠습니까?"
                this.dialog = true
            },
            del() {
                let value2 = {
                    user_id: this.user_id
                }
                axios.delete("/api/attend_posts/" + this.posts.id, {
                        params: value2
                    })
                    .then(response => {
                        alert(response.data.message)

                        if (response.data.status != "false") {
                            window.location.href = '/runauth?page=' + this.pageId;
                        }
                    })
            },
            closeDialog() {
                this.dialog = false
            },
            moveList() {
                window.location.href = '/runauth/?page=' + this.pageId
            },
            editpost() {
                window.location.href = '/edit/' + this.postId + '?page=' + this.pageId
            },
            submit() {
                let value = {
                    user_id: this.user_id,
                    content: this.content,
                }
                axios.post(`/api/post/${this.postId}/comment`, null, {
                        params: value
                    })
                    .then(response => {
                        alert("댓글 작성되었습니다");
                        this.refresh();
                    })
            },
            refresh() {
                axios.get("/api/attend_posts/" + this.postId + '?page=' + this.commentPage)
                    .then(response => {
                        this.posts = response.data.post[0]
                        this.comments = response.data.comment.data
                        this.commentLinks = response.data.comment
                        this.content = ''
                    })
            },
            refreshPage(page) {
                if (page == "<") {
                    if (this.commentLinks.links[0].url) {
                        page = this.commentLinks.links[0].url.charAt(this.commentLinks.links[0].url.length - 1)
                    } else {
                        page = 1
                    }
                }
                if (page == ">") {
                    if (this.commentLinks.links[this.commentLinks.last_page + 1].url) {
                        page = this.commentLinks.links[this.commentLinks.last_page + 1].url.charAt(this.commentLinks
                            .links[this.commentLinks.last_page + 1].url.length - 1)
                    } else {
                        page = this.commentLinks.last_page;
                    }
                }
                axios.get("/api/attend_posts/" + this.postId + '?page=' + page)
                    .then(response => {
                        this.commentPage = page;
                        this.posts = response.data.post[0]
                        this.comments = response.data.comment.data
                        this.commentLinks = response.data.comment
                        console.log(this.commentLinks)
                        this.content = ''
                    })
            },
            deleteComment(commentID) {
                axios.delete('/api/comment/' + commentID)
                    .then(response => {
                        alert("삭제 되었습니다")
                        this.refresh();
                    })
            },
            editComment(commentID, content) {
                axios.patch('/api/comment/' + commentID, {
                        content: content
                    })
                    .then(response => {
                        alert("수정 되었습니다")
                        this.closeEditComment()
                    })
            },
            editLayout(commentID) {
                this.refresh()
                this.commentID = commentID;
            },
            closeEditComment() {
                this.commentID = '';
                this.refresh();
            }
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>