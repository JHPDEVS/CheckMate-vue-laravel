<template>
      <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                공지사항
            </h2>
        </template>

        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               
                <ul>
                    <li v-for="(post,i) in posts" v-bind:key="i">
                        {{ post.title }}
                        {{ post.href }}
                        {{ post.time  }}
                    </li>
                </ul>
       
            </div>
        </div>
    </app-layout>
</template>

<script>

    import AppLayout from '@/Layouts/AppLayout'
    import axios from 'axios'
    import cheerio from "cheerio"
export default {
      components: {
            AppLayout
        },
     data: () => ({
      posts: [{
          title: ''
      }],
    }),
    computed: {

    },
         mounted() {

                axios.get("https://cors-anywhere.herokuapp.com/https://computer.yju.ac.kr/board_NZIo01")
                .then(response=>{
                    const $ = cheerio.load(response.data);
                    const span = $('tbody').children('tr');
                    var posts = []
                    span.map(function(i,elem) {
                       if(i<=2) {
                       var time = $(this).find('.time').text()
                       var author = $(this).find('.author').text()
                       var title = $(this).find('.title a strong').text()
                       var href = $(this).find('.title a').attr('href')
                       } else if(i>3) {
                        var time = $(this).find('.time').text()
                       var author = $(this).find('.author').text()
                       var title = $(this).find('.title a').text().trim()
                       var href = $(this).find('.title a').attr('href')
                       }
                       let value = {
                           time: time,
                           author: author,
                           title: title,
                           href: href
                       }
                        posts.push(value);
                       
                   })
                    this.posts = posts;
                })
                   
        },
        methods: {
           parsing() {
       
           }
    },
}
</script>
