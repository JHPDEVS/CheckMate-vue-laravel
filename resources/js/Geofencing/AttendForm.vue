<template>
    <form class="w-full max-w-sm">
        <div class="flex items-center border-b border-teal-500 py-2">
            <input
                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                readonly type="text" :value="user_name">
            <input
                class="flex-shrink-0 bg-red-500 hover:bg-teal-700 border-red-500 hover:border-red-700 text-sm border-4 text-white py-1 px-2 rounded"
                type="button" @click="attend" value="출석">
            <input type="hidden" name="_token" :value="csrf">
        </div>
    </form>

    <input
        class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
        readonly type="text" value="최근 출석 시간: 08-03 11:12:54">
</template>

<script>
    export default {
        data: function () {
            return {
                msg: '',
                user_name: document.head.querySelector('meta[name="user-name"]').content,
                user_sid: document.head.querySelector('meta[name="user-sid"]').content,
                user_class: document.head.querySelector('meta[name="user-class"]').content,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
            }
        },
        methods: {
            attend() {
                let value = { user_sid : this.user_sid }
                axios.post('/api/attends',null,{params: value})
                    .then(response => {
                        console.log(response);
                    })
                    .catch(response => {
                      console.log(response)
                    })
            }
        }
    }
</script>