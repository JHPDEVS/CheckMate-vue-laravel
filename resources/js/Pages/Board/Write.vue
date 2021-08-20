<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                게시글 작성
            </h2>
        </template>
        <div class="flex items-center justify-center w-screen lg:px-24 py-2">
    <div class="editor  flex flex-col text-gray-800 border border-gray-300 p-4 shadow-lg max-w-2xl min-w-full">
    <input class="title bg-gray-100 border border-gray-300 p-2 mb-4 outline-none" spellcheck="false" placeholder="바퀴" maxlength='3' type="number" v-model="run">
    <textarea class="description bg-gray-100 sec p-3 h-60 border border-gray-300 outline-none" spellcheck="false" placeholder="내용" v-model="content"></textarea>
    
    <!-- icons -->
    <div class="icons flex text-gray-500 m-2">
      <svg class="mr-2 cursor-pointer hover:text-gray-700 border rounded-full p-1 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="upload"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
      </svg>
      <div class="count ml-auto text-gray-400 text-xs font-semibold">0/300</div>
    </div>
    <input type="file" @change="upload">
    <!-- buttons -->
    <div class="buttons flex">
      <a class="btn border border-gray-300 p-1 px-4 font-semibold cursor-pointer text-gray-500 ml-auto" v-bind:href="`/runauth?page=${pageId}`">취소</a>
      <div class="btn border border-indigo-500 p-1 px-4 font-semibold cursor-pointer text-gray-200 ml-2 bg-indigo-500" @click="write">작성</div>
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
            run: '',
            content: '',
            imageFile: '',
            posts: [],
            pageId: '',
            ifLoading: 0,
            uploadReady: 0,
            user_id: document.head.querySelector('meta[name="user-id"]').content,
        }),
        computed: {

        },
        mounted() {
            const pageId = window.location.href.charAt(window.location.href.length-1)
            console.log(pageId);
            this.pageId = pageId;
        },
        methods: {
           write() {
               let value = {
                'user_id' : this.user_id,
                'content' : this.content,
                'run' : this.run,
                'imageFile' : this.imageFile
               }
               axios.post('/api/attend_posts/create',null, {
                    headers: {'Content-Type': 'multipart/form-data'} ,
                    params: value
                })
                .then(response => {
                        console.log(response);
                        alert("게시글 작성 완료")
                        window.location.href = '/runauth?page=1';
                    })
           },
           upload(event) {
               this.imageFile = event.target.files[0];
           }
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>