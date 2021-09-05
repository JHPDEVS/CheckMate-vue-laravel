<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                게시글 수정
            </h2>
        </template>
        <div class="flex items-center justify-center w-screen lg:px-24 py-2">
            <div class="editor  flex flex-col text-gray-800 border border-gray-300 p-4 shadow-lg max-w-2xl min-w-full">
                <input class="title bg-gray-100 border border-gray-300 p-2 mb-2 outline-none" spellcheck="false"
                    placeholder="바퀴 수" maxlength='3' type="number" v-model="run">
                <span v-if="msg.run" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ msg.run }}</span>
                <textarea class="description bg-gray-100 sec p-3 h-60 border border-gray-300 outline-none"
                    spellcheck="false" placeholder="내용" v-model="content"></textarea>
                <span v-if="msg.content" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ msg.content }}</span>
                <!-- icons -->
                <div v-if="!imageFile" class="py-4">
                    <input type="file" @change="upload">
                </div>
                <div v-else class="py-4">
                    
                <div v-if="url">
                 <div id="preview" class="indicator">
                        <div class="indicator-item badge" @click="removeImage">X</div> 
                        <img :src="url" class="object-scale-down h-60"/>
                    </div>
                </div>
                <div >
                    <div v-if="!url" class="indicator">
                    <div class="indicator-item badge" @click="removeImage">X</div> 
                    <img v-bind:src="`/storage/images/${posts.image}`" class="object-scale-down h-60"/>
                    </div>
                </div>
                </div>
                    <span v-if="msg.imageFile" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ msg.imageFile }}</span>
                <!-- buttons -->
                <div class="buttons flex">
                    <a class="btn border border-gray-300 p-1 px-4 font-semibold cursor-pointer text-gray-500 ml-auto"
                        v-bind:href="`/runauth/show/${postId}?page=${pageId}`">취소</a>
                    <div class="btn border border-indigo-500 p-1 px-4 font-semibold cursor-pointer text-gray-200 ml-2 bg-indigo-500"
                        @click="write">작성</div>
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
            msg:'',
            run: '',
            content: '',
            imageFile: '',
            posts: [],
            pageId: '',
            postId: '',
            ifLoading: 0,
            url: '',
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
                    this.run = this.posts.run
                    this.content = this.posts.content;
                    this.imageFile = this.posts.image;
                })
        },
        methods: {
            write() {
                const bodyFormData = new FormData();
                if(this.imageFile) {
                 bodyFormData.append('imageFile',this.imageFile);
                }
                bodyFormData.append("_method", "put");
                console.log(bodyFormData)
                axios.post('/api/attend_posts/' + this.postId, bodyFormData, {
                        params: {
                            'user_id' : this.user_id,
                            'content' : this.content,
                            'run' : this.run,
                        },
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    })
                    .then(response => {
                        console.log(response);

                        if(response.data.status == "success") {
                            this.msg = ''
                          alert("게시글 수정 완료")
                             window.location.href = '/runauth/show/' + this.postId + '?page=' + this.pageId;
                        } else {
                            this.msg = response.data.data
                            console.log(this.msg)
                        }
                    })
            },
            upload(event) {
                this.imageFile = event.target.files[0];
                this.url = URL.createObjectURL(this.imageFile);
                console.log(this.url)
            },
            removeImage() {
                this.imageFile = '';
                console.log("이미지 삭제")
            },
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>