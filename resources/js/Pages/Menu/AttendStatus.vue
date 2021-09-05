<template>
    <app-layout>
        <attend-marquee/>
        <template #header>
            <div class="tabs">
                <a class="tab  tab-lifted tab-lg tab-active font-bold" :href="route('attendstatus')">My 출석</a>
                <a class="tab  tab-lifted tab-lg font-bold" :href="route('classAttendStatus')">전체 출석</a>
            </div>
        </template>

        <div class="md:px-24 py-4 w-full">

            <div class="overflow-hidden">

                <div class="pb-4 mx-auto">
                    <attend-chart />
                </div>
                <div class="tabs">
                    <button class="tab  tab-lifted tab-lg tab-active font-bold" v-if="category=='전체'"
                        @click="refresh('전체')">전체</button>
                    <button class="tab  tab-lifted tab-lg font-bold" v-else @click="refresh('전체')">전체</button>
                    <button class="tab  tab-lifted tab-lg tab-active font-bold" v-if="category=='출석'"
                        @click="refresh('출석')">출석</button>
                    <button class="tab  tab-lifted tab-lg font-bold" v-else @click="refresh('출석')">출석</button>
                    <button class="tab  tab-lifted tab-lg tab-active font-bold" v-if="category=='지각'"
                        @click="refresh('지각')">지각</button>
                    <button class="tab  tab-lifted tab-lg font-bold" v-else @click="refresh('지각')">지각</button>
                    <button class="tab  tab-lifted tab-lg tab-active font-bold" v-if="category=='결석'"
                        @click="refresh('결석')">결석</button>
                    <button class="tab  tab-lifted tab-lg font-bold" v-else @click="refresh('결석')">결석</button>
                </div>
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="bg-white px-4 py-3   items-center justify-between border-t border-gray-200 sm:px-6 lg:flex">
                    <div class="flex-1 flex items-center justify-between ">
                        <div>
                            <nav class="relative z-0 inline-flex flex-nowrap rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                                <a v-for="(item,i) in pageLinks" v-bind:key="i">
                                    <button @click="refreshByPage(item.label)" v-if="item.active==true"
                                        class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ item.label }}</button>
                                    <button @click="refreshByPage(item.label)" v-else
                                        class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-mediums">{{ item.label }}</button>
                                </a>

                            </nav>

                        </div>

                    </div>
                </div>
                <table class="table table-compact min-w-full table-zebra">
                    <thead class="text-center bold">
                        <tr>
                            <th></th>
                            <th><i class="far fa-question-circle"></i>상태</th>
                            <th><i class="far fa-flag"></i>바퀴</th>
                            <th><i class="far fa-clock"></i>시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(attend,i) in attends" v-bind:key=i class="text-center justify-center">
                            <th>{{data.from + i}}</th>
                            <td v-if="attend.desc_value=='출석'" class=" badge-success"><i class="fas fa-check"> 출석</i>
                            </td>
                            <td v-else-if="attend.desc_value=='지각'" class=" badge-warning"><i
                                    class="fas fa-exclamation"> 지각</i></td>
                            <td v-else class="badge-error"><i class="fas fa-times"> 결석</i></td>
                            <td>{{ attend.run }}</td>
                            <td>{{ attend.date }}</td>
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
    import BadgeYellow from '@/Geofencing/BadgeYellow'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    import AppLayout from '@/Layouts/AppLayout'
    import AttendChart from './AttendChart.vue'
    export default {
        data: function () {
            return {
                currentPage: 1,
                category: '전체',
                header: '',
                header2: '',
                msg: '',
                data: [],
                pageLinks: [],
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
            }
        },
        components: {
            AppLayout,
            Dialog,
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
            LoadingBar,
            AttendChart,
        },
        mounted() {
            axios.get('/api/user/attendStatus/' + this.user_id + '?page=' + this.currentPage + '&attend=전체', null)
                .then(response => {
                    this.attends = response.data.user_attend.data;
                    this.pageLinks = response.data.user_attend.links
                    this.data = response.data.user_attend;
                    this.isLoading = 1;
                    this.currentPage = response.data.user_attend.current_page;
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {
            refresh(category) {
                axios.get('/api/user/attendStatus/' + this.user_id + '?page=1' + '&attend=' + category, null)
                    .then(response => {
                        this.attends = response.data.user_attend.data;
                        this.pageLinks = response.data.user_attend.links
                        this.data = response.data.user_attend;
                        this.isLoading = 1;
                        this.currentPage = 1;
                        this.category = category;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            refreshByPage(page) {
                if (page == "<") {
                    if (this.pageLinks[0].url) {
                        page = this.pageLinks[0].url.charAt(this.pageLinks[0].url.length - 1)
                    } else {
                        page = 1
                    }
                }
                if (page == ">") {
                    if (this.pageLinks[this.data.last_page + 1].url) {
                        page = this.pageLinks[this.data.last_page + 1].url.charAt(this.pageLinks[this.data.last_page +
                            1].url.length - 1)
                    } else {
                        page = this.data.last_page;
                    }
                }
                axios.get('/api/user/attendStatus/' + this.user_id + '?page=' + page + '&attend=' + this.category, null)
                    .then(response => {
                        this.attends = response.data.user_attend.data;
                        this.pageLinks = response.data.user_attend.links
                        this.data = response.data.user_attend;
                        this.isLoading = 1;
                        this.currentPage = response.data.user_attend.current_page;
                    })
                    .catch(err => {
                        console.log(err);
                    })
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