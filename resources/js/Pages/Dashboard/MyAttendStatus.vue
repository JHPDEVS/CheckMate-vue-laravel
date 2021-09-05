<template>
    <!-- component -->
    <div class="flex  flex-col  md:flex-row justify-center  flex-wrap gap-3  ">
        <div class="min-w-full p-4">
            <div
                class=" bg-white max-w-full shadow-lg   mx-auto border-b-4 border-indigo-500 rounded-2xl overflow-hidden  hover:shadow-2xl transition duration-500 transform hover: cursor-pointer">
                <div class="bg-indigo-500  flex h-20  items-center">
                    <h1 class="text-white ml-4 border-2 py-2 px-4 rounded-full font-bold">{{ user_name }}</h1>
                    <p class="ml-2 text-white uppercase">님의 달리기 정보</p>
                </div>
                <div class="flex justify-center p-3 text-lg gap-5" v-if="run.countRun">
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-indigo-100 text-indigo-700 hover:bg-indigo-500 hover:text-white`">
                        <i class="fas fa-flag text-xl"><span>{{ run.totalRun }}</span></i>
                    </div>
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-green-100 text-green-700 hover:bg-green-500 hover:text-white`">
                        <i class="fas fa-running text-xl"><span>{{ run.minusRun }}</span></i>

                    </div>
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-red-100 text-red-700 hover:bg-red-500 hover:text-white`">
                        <i class="fas fa-flag text-xl"><span>{{ run.countRun }}</span></i>
                    </div>
                </div>
                <div v-else class="flex justify-center p-3 text-lg gap-5 badge-error font-bold">달리기 정보 없음</div>
                <!-- <hr > -->
                <hr>
                <div class="flex justify-center p-3 text-sm gap-2">
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-indigo-100 text-indigo-700 hover:bg-indigo-500 hover:text-white`">
                        <i class="fas fa-flag text-xs"><span>총 바퀴</span></i>
                    </div>
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-green-100 text-green-700 hover:bg-green-500 hover:text-white`">
                        <i class="fas fa-running text-xs"><span>달린 바퀴</span></i>
                    </div>
                    <div class="px-2 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
                        :class="`bg-red-100 text-red-700 hover:bg-red-500 hover:text-white`">
                        <i class="fas fa-flag text-xs"><span>남은 바퀴</span></i>
                    </div>

                </div>
            </div>
        </div>


    </div>


</template>

<script>
    // import the styles 

    export default {
        data: () => ({
            ranking: [],
            run: [],
            user_id: document.head.querySelector('meta[name="user-id"]').content,
            user_photo: document.head.querySelector('meta[name="user-photo"]').content,
            user_name: document.head.querySelector('meta[name="user-name"]').content,
            user_sid: document.head.querySelector('meta[name="user-sid"]').content,
        }),
        components: {

        },
        mounted() {
            axios.get("/api/user/attendStatus/" + this.user_id + '?attend=지각')
                .then(response => {
                    this.run = response.data.user_run.data[0]
                })




        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
        },
    }
</script>
