<template>
    <!-- component -->
    <div class="flex  flex-col  md:flex-row justify-center  flex-wrap gap-3  ">
        <div class="min-w-full p-4">
            <div
                class=" bg-white max-w-full shadow-lg   mx-auto border-b-4 border-indigo-500 rounded-2xl overflow-hidden  hover:shadow-2xl transition duration-500 transform hover: cursor-pointer">
                <div class="bg-indigo-500  flex h-20  items-center">
                    <h1 class="text-white ml-4 border-2 py-2 px-4 rounded-full">{{ user_name }}</h1>
                    <p class="ml-2 text-white uppercase">님의 자유게시판 정보</p>
                </div>
                <div class="flex justify-center p-3 text-lg gap-5">
                <i class="fas fa-clipboard px-1 badge-info hover:shadow-2xl transition duration-500 transform hover:scale-150 cursor-pointer"><span> {{ info.countMyPosts }}개</span></i>
                <i class="fas fa-comments px-1 badge-success hover:shadow-2xl transition duration-500 transform hover:scale-150 cursor-pointer"><span> {{ info.countMyComments }}개 </span></i>
                </div>
                <!-- <hr > -->
                <hr>
                <div class="flex justify-center p-3 text-sm gap-3">
                            <i class="fas fa-clipboard px-1 badge-info"><span>작성 게시글 개수</span></i>
                <i class="fas fa-comments px-1 badge-success"><span>작성 댓글 개수</span></i>
                </div>
            </div>
        </div>


    </div>


</template>

<script>
    // import the styles 

    export default {
        data: () => ({
            info: [],
            user_name: document.head.querySelector('meta[name="user-name"]').content,
            user_id: document.head.querySelector('meta[name="user-id"]').content,
            
        }),
        components: {

        },
        mounted() {
            axios.get("/api/user/" + this.user_id + '/count_posts_comments')
                .then(response => {
                    this.info = response.data.data
                })




        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
        },
    }
</script>
