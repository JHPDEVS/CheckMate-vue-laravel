<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                달리기 인증 게시판
            </h2>
        </template>
        <div class="md:px-32 py-4 w-full">

            <div class="overflow-hidden">
                <a class="btn btn-secondary" v-bind:href="`/write?page=${page.current_page}`">글쓰기</a>
                <table class="table-compact min-w-full ">
                    <thead class="text-center">
                        <tr>
                            <th></th>
                            <th>제목</th>
                            <th>상태</th>
                            <th>시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(post,i) in posts" v-bind:key=i class="text-center justify-center">
                            <th>{{post.id}}</th>
                            <th><a v-bind:href="`/runauth/show/${post.id}?page=${page.current_page}`">{{ post.name }} : {{ post.run }} 바퀴</a></th>
                            <th v-if="post.flag==0" class=" badge-error">X</th>
                            <th v-else class="badge-success">O</th>
                            <th>{{ post.updated_at }}</th>
                        </tr>
                    </tbody>
                </table>
 <div class="btn-group">
  <span v-for="(item) in pageLinks" v-bind:key="item">
      <a v-if="item.active ==true && typeof(+item.label == 'number')" v-bind:href="`/runauth/?page=${item.label}`" class="btn btn-sm btn-success">{{ item.label }}</a>
      <a v-else  v-bind:href="`/runauth/?page=${item.label}`" class="btn btn-sm">{{ item.label }}</a>
      </span> 
</div>

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
            }
        },
        components: {
            AppLayout,
            Dialog,
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
            LoadingBar,
        },
        mounted() {
            var {
                pathname
            } = window.location
            var splitPathname = pathname.split("/")
            const postId = splitPathname[splitPathname.length - 1]
            var pageId = parseInt(window.location.href.charAt(window.location.href.length-1))
            console.log(postId);
            console.log(pageId);
            if(Number.isInteger(pageId)) {
                console.log("현재 페이지")
                this.pageId = pageId;
            } else {
                console.log("첫페이지로")
            }
            axios.get('/api/attend_posts/index?page='+ this.pageId)
                .then(response => {
                    this.page = response.data.posts;
                    this.pageId = response.data.posts.current_page
                    this.posts = response.data.posts.data;
                    this.isLoading = 1;
                    this.pageLinks = response.data.posts.links
                    this.pageLinks.splice(0,1) // 이전 삭제
                    this.pageLinks.splice(this.pageLinks.length-1,1) // 다음 삭제

                    console.log(this.pageId);
                    console.log(this.pageLinks)
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {

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