<template>
    <app-layout>
        <template #header>
<div class="tabs">
  <a class="tab  tab-lifted tab-lg " :href="route('attendstatus')">My 출석</a> 
  <a class="tab  tab-lifted tab-lg tab-active" :href="route('classAttendStatus')">전체 출석</a> 
  </div>
        </template>
        <div class="md:px-32  w-full">
                    <attend-marquee/>
  <div class="lg:flex no-wrap" v-if="total != 0">
 <div class="min-w-full  sm:mx-auto ">
    <div class="sm:flex sm:space-x-4">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow transform transition-all mb-4 w-full sm:w-1/3 sm:my-8">
            <div class="bg-white p-5">
                <div class="sm:flex sm:items-start">
                    <div class="text-center sm:mt-0 sm:ml-2 sm:text-left">
                        <h3 class="text-sm leading-6 font-bold">출석</h3>
                        <p class="text-3xl font-bold text-success"><i class="fas fa-check"></i> {{ attend }} </p>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow transform transition-all mb-4 w-full sm:w-1/3 sm:my-8">
            <div class="bg-white p-5">
                <div class="sm:flex sm:items-start">
                    <div class="text-center sm:mt-0 sm:ml-2 sm:text-left">
                        <h3 class="text-sm leading-6 font-bold">지각</h3>
                        <p class="text-3xl font-bold text-warning"><i class="fas fa-clock"></i> {{ late }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow transform transition-all mb-4 w-full sm:w-1/3 sm:my-8">
            <div class="bg-white p-5">
                <div class="sm:flex sm:items-start">
                    <div class="text-center sm:mt-0 sm:ml-2 sm:text-left">
                        <h3 class="text-sm leading-6 font-bold">결석</h3>
                        <p class="text-3xl font-bold text-error"><i class="fas fa-times"></i> {{ absence }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   
        
    </div>
            
            <div
                class="flex  items-center self-center min-w-full max-w-xl p-4 mt-4 text-gray-600 focus-within:text-gray-400 indicator">
                <div class="indicator-item indicator-center badge badge-secondary">날짜를 클릭하여 날짜를 변경하세요</div>
                <datepicker ref="picker" type="search" v-model="picked" :locale="locale"
                    class="  text-sm bg-gray-100 border border-transparent appearance-none rounded-tg placeholder-gray-400 focus:bg-white focus:outline-none focus:border-blue-500 focus:text-gray-900 focus:shadow-outline-blue"
                    style="border-radius: 25px, white-space: pre" placeholder="날짜" />
                <button class="btn" @click="bandShare">밴드 공유</button>
            </div>
            <div class="overflow-hidden">
                <div class="mx-auto">
                </div>
                <div v-if="total ==0">
                    <div class="alert alert-error">
                        <div class="flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="w-6 h-6 mx-2 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <label>출석정보 없음</label>
                        </div>
                    </div>
                </div>
                <table class="table table-compact min-w-full table-zebra">
                    <thead class="text-center bold">
                        <tr>
                            <td></td>
                            <th><i class="far fa-question-circle"></i>이름</th>
                            <th><i class="far fa-flag"></i>상태</th>
                            <th><i class="far fa-clock"></i>날짜</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(attend,i) in attends" v-bind:key=i class="text-center justify-center">
                            <td>{{i+1}}</td>
                            <td>{{ attend.name }}</td>
                            <td v-if="attend.desc_value=='출석'" class=" badge-success"><i class="fas fa-check"> 출석</i>
                            </td>
                            <td v-else-if="attend.desc_value=='지각'" class=" badge-warning"><i
                                    class="fas fa-exclamation"> 지각</i></td>
                            <td v-else class="badge-error"><i class="fas fa-times"> 결석</i></td>
                            <td>{{ attend.date}}</td>
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
    import AttendMarquee from '@/Geofencing/AttendMarquee'
    import BadgeYellow from '@/Geofencing/BadgeYellow'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    import AppLayout from '@/Layouts/AppLayout'
    import Datepicker from 'vue3-datepicker'
    import ko from "date-fns/locale/ko"
    import moment from 'moment'
    import LineChart from './DonutChart.vue'
    import 'chartjs-plugin-labels';
    export default {
        head() {
            return {
                script: [{
                    src: '//developers.band.us/js/share/band-button.js?v=27082021'
                }, ],
            }
        },
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
                attends: [],
                isLoading: 0,
                showAttend: false,
                picked: new Date(),
                locale: ko,
                date: '',
                attend: 0,
                late: 0,
                laters: [],
                absencers: [],
                absence: 0,
            }
        },
        components: {
            AppLayout,
            Dialog,
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
            LoadingBar,
            Datepicker,
            LineChart,
            AttendMarquee
        },
        mounted() {
            this.date = moment(this.picked).format('yyyy-MM-DD')
            axios.get('/api/class/2?date=' + this.date, null)
                .then(response => {
                    this.total = response.data.data.total;
                    this.pageId = response.data.data.current_page
                    this.attends = response.data.data.data;
                    this.isLoading = 1;

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
        watch: {
            picked: function () {
                this.refresh()
            }
        },
        methods: {

            refresh() {
                this.date = moment(this.picked).format('yyyy-MM-DD')
                axios.get('/api/class/2?date=' + this.date, null)
                    .then(response => {
                        this.attend = 0
                        this.late = 0
                        this.absence = 0
                        this.total = response.data.data.total;
                        this.pageId = response.data.data.current_page
                        this.attends = response.data.data.data;
                        this.isLoading = 1;
                        this.absencers = [];
                        this.laters = [];
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
                    this.count = [this.attend,this.late,this.absence]
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            openCalendar() {
                this.$refs.picker.$el.click();
            },
            bandShare() {
                if (this.isMobile()) {
                      var string = this.date +'%0A'+ '지각 : ' + this.late+"명 " + this.laters +'%0A결석 : ' + this.absence+"명 " + this.absencers +"%0A오늘 출석현황 : " + "https://ckme.live/classAttendStatus";
                       var url = 'bandapp://create/post?text=' +string + '&route=ckme.live'
                       window.open(url,"_blank");
                } else {
                       var string = this.date +'%0A'+ '지각 : ' + this.late+"명 " + this.laters +'%0A결석 : ' + this.absence+"명 " + this.absencers +"%0A오늘 출석현황 : " + "https://ckme.live/classAttendStatus";
                       var url = 'https://band.us/plugin/share?body=' +string + '&route=ckme.live'
                       window.open(url,"_blank");
                }
            },
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            },
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