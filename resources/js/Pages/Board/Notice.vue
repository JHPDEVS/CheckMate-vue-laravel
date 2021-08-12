<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                공지사항
            </h2>
        </template>
        <div class="md:px-8 py-4 w-full">
            <div class="shadow overflow-hidden rounded border-b border-gray-200">
                <table v-if="ifLoading<1" class="flex items-center justify-center min-w-full bg-white">
                    <loading-bar />
                </table>
                <table v-else class="min-w-full bg-white ">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="w-2/3 text-left py-3 font-semibold text-sm text-center">제목</th>
                            <th class="text-left py-3 px-4  font-semibold text-sm text-center">작성 일</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 divide-y divide-gray-300 ">
                        <tr v-for="(post,i) in posts" v-bind:key="i">
                            <!-- 모바일  -->
                            <td v-if="this.isMobile() && i<3"
                                class="w-2/3 text-left  px-4 text-center text-red-500"><a
                                    v-bind:href='post.href'>{{ post.title }}</a></td>
                            <td v-else-if="this.isMobile() && i>=3" class="w-1/3 text-left px-4 text-center "><a
                                    v-bind:href='post.href'>{{ post.title }}</a></td>

                            <!-- PC버전 -->
                            <td v-if="!this.isMobile() && i<3"
                                class="w-2/3 text-left py-3 px-4 text-center text-red-500"><a
                                    v-bind:href='post.href'>{{ post.title }}</a></td>
                            <td v-else-if="!this.isMobile() && i>=3" class="w-1/3 text-left py-3 px-4 text-center "><a
                                    v-bind:href='post.href'>{{ post.title }}</a></td>
                            <td class="py-3 px-4 text-center"><a class="hover:text-blue-500">{{ post.time }}</a></td>
                        </tr>
                    </tbody>
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
            LoadingBar
        },
        data: () => ({
            posts: [],
            ifLoading: 0,
        }),
        computed: {

        },
        mounted() {
            if (this.isMobile()) {
                // 모바일이면 실행될 코드 들어가는 곳
                // https://cors-anywhere.herokuapp.com/https://computer.yju.ac.kr/board_NZIo01
                // http://api.ckmate.shop/https://computer.yju.ac.kr/board_NZIo01
                axios.get("https://api.ckmate.shop/https://computer.yju.ac.kr/board_NZIo01")
                    .then(response => {
                        const $ = cheerio.load(response.data);
                        const span = $('ol').children('li');
                        var posts = []
                        span.map(function (i, elem) {

                            var title = $(this).find('.rt_area h3').text()
                            var time = $(this).find('.info span b').first().text()
                            var href = $(this).find('a:nth-child(2)').attr('href')

                            let value = {
                                time: time,
                                title: title,
                                href: href
                            }
                            console.log(value);
                            posts.push(value);

                        })
                        this.posts = posts;
                        this.ifLoading = 1;
                    })
            } else {
                // 모바일이 아니면 실행될 코드 들어가는 곳
                axios.get("https://api.ckmate.shop/https://computer.yju.ac.kr/board_NZIo01")
                    .then(response => {
                        const $ = cheerio.load(response.data);
                        const span = $('tbody').children('tr');
                        var posts = []
                        span.map(function (i, elem) {

                            var time = $(this).find('.time').text()
                            var title = $(this).find('.title a').text().trim()
                            var href = $(this).find('.title a').attr('href')

                            let value = {
                                time: time,
                                title: title,
                                href: href
                            }
                            posts.push(value);
                           
                        })
                        posts.shift();
                        this.posts = posts;
                        this.ifLoading = 1;
                    })
            }


        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
        },
    }
</script>
<style>
    a:hover {
        text-decoration: underline;
    }
</style>