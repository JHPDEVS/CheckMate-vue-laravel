<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                시간표
            </h2>
        </template>
        <div class="md:px-16 py-4 w-full">
            <div class="shadow overflow-hidden rounded border-b border-gray-200">
                <table v-if="ifLoading<1" class="flex items-center justify-center min-w-full bg-white">
                    <loading-bar />
                </table>
                <table v-else class="min-w-full table-auto ">
                    <thead class="bg-gray-800 text-white border-y-2 border-gray-300 ">
                        <tr class="border border-gray-600">
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600"></th>
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600">월</th>
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600">화</th>
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600">수</th>
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600">목</th>
                            <th class="w-1/6 text-left py-3 font-semibold text-sm text-center border border-white-600">금</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide divide-black-900">
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td class=""> 9</td>
                            <template v-for="time in t9" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour" class="w-1/6 text-left py-3 font-semibold text-sm text-center bg-red-300">{{ time.classTitle }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>10</td>
                            <template v-for="time in t10" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour">{{ time.classTitle }}</td>
                            </template> 
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td class="">11</td>
                            <template v-for="time in t11" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour" class="bg-red-300  border-collapse border border-gray-500">{{ time.classTitle }} <br> {{ time.classPlace }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>12</td>
                            <template v-for="time in t12" v-bind:key="time">
                                <td :rowspan="(time.EndTime- time.startHour)+1"  class="bg-blue-300 border-collapse border border-gray-500">{{ time.classTitle }} <br> {{ time.classPlace }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>13</td>
                            <template v-for="time in t13" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour">{{ time.classTitle }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>14</td>
                            <template v-for="time in t14" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour" class="bg-green-300 border-collapse border border-gray-500">{{ time.classTitle }} <br> {{ time.classPlace }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>15</td>
                            <template v-for="time in t15" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour" class="bg-yellow-300 border-collapse border border-gray-500">{{ time.classTitle }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>16</td>
                            <template v-for="time in t16" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour" class="bg-purple-300 border-collapse border border-gray-500">{{ time.classTitle }} <br> {{ time.classPlace }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>17</td>
                            <template v-for="time in t17" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour">{{ time.classTitle }}</td>
                            </template>
                        </tr>
                        <tr class="w-1/6 text-left py-3 font-semibold text-sm text-center">
                            <td>18</td>
                            <template v-for="time in t18" v-bind:key="time">
                                <td :rowspan="time.EndTime- time.startHour">{{ time.classTitle }}</td>
                            </template>
                        </tr>

                    </tbody>

                    <!--/ College Timetable
-->

                </table>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import axios from 'axios'
    import cheerio from "cheerio"
    import LoadingBar from "@/Pages/Board/LoadingBar"
    export default {
        components: {
            AppLayout,
            LoadingBar,

        },
        data: () => ({
            t9: [],
            t10: [],
            t11: [],
            t12: [],
            t13: [],
            t14: [],
            t15: [],
            t16: [],
            t17: [],
            t18: [],
            ifLoading: 0,
        }),
        computed: {

        },
        mounted() {


            axios.get("api/timetables")
                .then(res => {
                    this.data = res.data.data;
                    this.data.sort(function (a, b) {
                        return a.day - b.day
                    })
                    this.data.forEach(element => {
                        if (element.startHour == 9) {
                            this.t9.splice(element.day, 1, element);
                        } else if (element.startHour == 10) {
                            this.t10.splice(element.day, 1, element);
                        } else if (element.startHour == 11) {
                            this.t11.splice(element.day, 1, element);
                        } else if (element.startHour == 12) {
                           this.t12.splice(element.day, 1, element);
                        } else if (element.startHour == 13) {
                            this.t13.splice(element.day, 1, element);;
                        } else if (element.startHour == 14) {
                            this.t14.splice(element.day, 1, element);
                        } else if (element.startHour == 15) {
                            this.t15.splice(element.day, 1, element);
                        } else if (element.startHour == 16) {
                            this.t16.splice(element.day, 1, element);
                        } else if (element.startHour == 17) {
                           this.t17.splice(element.day, 1, element);
                        } else if (element.startHour == 18) {
                            this.t18.splice(element.day, 1, element);
                        }
                    });

            this.ifLoading = 1;
                })

        }



        ,
        methods: {},
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>