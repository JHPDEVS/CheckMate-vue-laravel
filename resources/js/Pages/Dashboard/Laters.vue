<template>

    <div class="card lg:card-side bordered compact">
        <div class="card-body">
            <h2 class="card-title font-semibolds">지각 랭킹
                <div class="badge mx-2">WDJ</div>
            </h2>
                <span>총 {{ total }}명 중 {{ from }}~{{ to }}위 표시중</span>
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

            <div class="shadow overflow-auto rounded border-b border-primary-600">
                <table class="table min-w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>이름</th>
                            <th>지각 수</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rank,i) in ranking" v-bind:key=i>
                            <th>
                                <label>
                                    {{ from +i }}
                                </label>
                            </th>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <div class="avatar">
                                        <div class="w-12 h-12 mask mask-squircle">
                                            <img v-bind:src="rank.profile_photo_path" v-bind:alt="rank.name">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">
                                            {{ rank.name }}
                                        </div>
                                        <div class="text-sm opacity-50">
                                            {{ rank.sid }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <th class="text-center">
                            <progress class="progress progress-warning" v-bind:value="rank.total_count"
                                            v-bind:max="max"> </progress>
                                            <br>
                                            {{ rank.total_count }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    // import the styles 

    export default {
        data: () => ({
        ranking: [],
        currentPage : 1,
        from : 0,
        to : 0,
        total : 0,
        max : 0,
        }),
        components: {

        },
        mounted() {
            axios.get("/api/users/latecomers?page=" +this.currentPage)
                .then(response => {
                    this.ranking = response.data.latecomers.wdj.data
                    this.pageLinks = response.data.latecomers.wdj.links
                    this.data = response.data.latecomers.wdj;
                    this.from = response.data.latecomers.wdj.from
                    this.to = response.data.latecomers.wdj.to
                    this.total = response.data.latecomers.wdj.total
                    this.isLoading = 1;
                    this.currentPage = 1;
                    this.max = this.ranking[0].total_count
                })



        },
        methods: {
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
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
                axios.get('/api/users/latecomers?page=' +page)
                    .then(response => {
                    this.ranking = response.data.latecomers.wdj.data
                    this.pageLinks = response.data.latecomers.wdj.links
                    this.data = response.data.latecomers.wdj;
                    this.from = response.data.latecomers.wdj.from
                    this.to = response.data.latecomers.wdj.to
                    this.total = response.data.latecomers.wdj.total
                    this.isLoading = 1;
                    this.currentPage = page;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
        },
    }
</script>
