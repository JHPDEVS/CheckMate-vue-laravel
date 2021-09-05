<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                게시글 작성
            </h2>
        </template>


        <!-- <ckeditor :editor="editor" v-model="editorData" :config="editorConfig" /> -->

        <div class="flex items-center justify-center w-screen lg:px-24 py-2">
            <div class="editor  flex flex-col text-gray-800 border border-gray-300 p-4 shadow-lg max-w-2xl min-w-full">
                <input class="title bg-gray-100 border border-gray-300 p-2 mb-2 outline-none" spellcheck="false"
                    placeholder="바퀴 수" maxlength='3' type="number" v-model="run">
                    <span v-if="msg.run" class="flex items-center font-medium tracking-wide text-red-500 text-xs  ml-1">{{ msg.run }}</span>
                <textarea class="description bg-gray-100 sec p-3 h-60 border border-gray-300 outline-none"
                    spellcheck="false" placeholder="내용" v-model="content"></textarea>
                <span v-if="msg.content" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ msg.content }}</span>
                <!-- icons -->
                <div class="icons flex text-gray-500 m-2">
                <div v-if="imageFile" class="w-64 h-36 carousel rounded-box carousel-vertical">
                 <div id="preview" class="w-1/2 carousel-item">
                        <img :src="url"/>
                    </div>
                </div>
                </div>
                <input type="file" accept="image/*" @change="upload" >
                 <span v-if="msg.imageFile" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ msg.imageFile }}</span>
                <!-- buttons -->
                <div class="buttons flex">
                    <a class="btn border border-gray-300 p-1 px-4 font-semibold cursor-pointer text-gray-500 ml-auto"
                        v-bind:href="`/runauth?page=${pageId}`">취소</a>
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
    // import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    // import CKEditor from '@ckeditor/ckeditor5-vue'

    export default {
        components: {
            AppLayout,
            LoadingBar,
            // ckeditor: CKEditor.component
        },
        data: () => ({
            msg: '',
            run: '',
            content: '',
            imageFile: '',
            posts: [],
            pageId: '',
            ifLoading: 0,
            uploadReady: 0,
            url:'',
            user_id: document.head.querySelector('meta[name="user-id"]').content,
        }),
        computed: {

        },
        mounted() {
            const pageId = window.location.href.charAt(window.location.href.length - 1)
            console.log(pageId);
            this.pageId = pageId;
        },
        methods: {
            write() {
                const bodyFormData = new FormData();
                bodyFormData.append('user_id', this.user_id);
                bodyFormData.append('content', this.content);
                bodyFormData.append('run', this.run);
                if(this.imageFile){
                bodyFormData.append('imageFile', this.imageFile);
                }
                console.log(bodyFormData)
                for(let key of bodyFormData.entries()){
                    console.log(`${key}`)
                }
                axios.post('/api/attend_posts/create',bodyFormData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    }).then(response => {
                        console.log(response.data.status)

                        if(response.data.status=="false") {
                        this.msg = response.data.data
                        console.log(this.msg)
                        } else {
                            this.msg = ''
                            window.location.href = '/runauth?page=1';
                        }
                    })
                    
            },
            upload(event) {
                this.imageFile = event.target.files[0];
                this.url = URL.createObjectURL(this.imageFile);
                console.log(this.url)
            },
            
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>