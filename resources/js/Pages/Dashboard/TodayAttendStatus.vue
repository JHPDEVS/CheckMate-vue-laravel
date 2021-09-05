<template>
    <!-- component -->
    <div class="flex  flex-col  md:flex-row justify-center  flex-wrap gap-3  ">
        <div class="min-w-full p-4">
            <div
                class=" bg-white max-w-full shadow-lg   mx-auto border-b-4 border-indigo-500 rounded-2xl overflow-hidden  hover:shadow-2xl transition duration-500 transform hover: cursor-pointer">
                <div class="bg-indigo-500  flex h-20  items-center">
                    <h1 class="text-white ml-4 border-2 py-2 px-4 rounded-full text-sm font-bold">오늘 출석현황</h1>
                    <p class="ml-2 text-white uppercase text-sm">{{ this.todayWithDay }}</p>
                </div>
                <div class="flex justify-center p-3 text-lg gap-3" v-if="total!=0">
                  <badge-green class="mx-1 text-2xl">{{ attend }}</badge-green>
                <badge-yellow class="mx-1 text-2xl">{{ late }}</badge-yellow>
                 <badge-red class="mx-1 text-2xl">{{ absence }}</badge-red>
                </div>
                <div v-else class="flex justify-center p-3  gap-5 badge-error font-bold">출석정보 없음</div>
                <!-- <hr > -->
                <!-- <hr>
                <div class="flex justify-center p-3 text-sm gap-2">
                    <badge-green class="mx-1 text-sm">출석</badge-green>
                       <badge-yellow class="mx-1 text-sm">지각</badge-yellow>
               <badge-red class="mx-1 text-sm">결석</badge-red>
             
                </div> -->
                <hr>
                                <a class="block mr-3 text-black text-right uppercase text-lg" :href="route('classAttendStatus')">더보기 > </a>
            </div>
        </div>


    </div>


</template>

<script>
    // import the styles 
    import BadgeRed from '@/Geofencing/BadgeRed'
    import BadgeGreen from '@/Geofencing/BadgeGreen'
    import BadgeYellow from '@/Geofencing/BadgeYellow'  
    export default {
        data: () => ({
            date: '',
            attend: 0,
            late: 0,
            absence: 0,
            attends: [],
            ranking: [],
            run: [],
            total: 0,
            laters: [],
            absencers: [],
            todayWithDay: '',
            user_id: document.head.querySelector('meta[name="user-id"]').content,
            user_photo: document.head.querySelector('meta[name="user-photo"]').content,
            user_name: document.head.querySelector('meta[name="user-name"]').content,
            user_sid: document.head.querySelector('meta[name="user-sid"]').content,
        }),
        components: {
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
        },
        mounted() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var week = new Array('일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일');
            var day = today.getDay();
            var dayLabel = week[day];
            this.todayWithDay =  yyyy + '-' + mm + '-' + dd + " ("+dayLabel+")";
            this.date = yyyy + '-' + mm + '-' + dd;
              axios.get('/api/class/2?date=' + this.date, null)
                .then(response => {
                    this.total = response.data.data.total;
                    this.attends = response.data.data.data;
                    this.attends.map(element => {
                        if(element.desc_value == "출석") {
                            ++this.attend
                        } else if(element.desc_value == "지각") {
                            ++this.late
                            this.laters.push(element.name)
                        } else {
                            ++this.absence
                            this.absencers.push(element.name)
                        }   
                    })
                    
                })
                .catch(err => {
                    console.log(err);
                })




        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
        },
    }
</script>
