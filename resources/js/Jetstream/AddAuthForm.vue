<template>
    <div class="py-3">
        <form action="/setInfo/save" method="post" v-on:submit.prevent>

            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="name">이름</label>
                <div class="form-control">
                    <input type="text" placeholder="홍길동" class="input input-success  input-bordered" v-model="name">
                    <span v-if="message.이름" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ message.이름 }}</span>
                </div>
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="phone_number">전화번호</label>
                <div class="form-control">
                    <input type="number" placeholder="username" class="input input-success  input-bordered"
                        v-model="phone_number">
                    <span v-if="message.전화번호" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ message.전화번호 }}</span>
                </div>
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="last_name">반</label>
                <select v-model='classValue' class="select select-bordered select-success  w-full max-w-xs">
                    <option disabled="disabled" selected="selected">WDJ</option>
                    <option>CPJ</option>
                </select>
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="sid">학번</label>
                <div class="form-control">
                    <input type="text" placeholder="1801234" class="input input-success  input-bordered" v-model="sid">
                    <span v-if="message.학번" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ message.학번 }}</span>
                </div>
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="password">직업</label>

                <select class="select select-bordered w-full max-w-xs" v-model="position" disabled="disabled">
                    <option disabled="disabled" selected="selected">학생</option>
                </select>

            </div>
            <input @click="save"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                value="제출">
            <input type="hidden" name="_token" :value="csrf">
        </form>
    </div>
</template>

<script>
    import Input from './Input.vue'
    import axios from 'axios'
    export default {
        data: function () {
            return {

                message: '',
                이름: '',
                phone_number: '010',
                sid: '',
                classValue: 'WDJ',
                position: '학생',

                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
            }
        },
        components: {

            Input
        },
        methods: {
            save() {
                axios.post('/setInfo/save', {
                        이름: this.name,
                        전화번호: this.phone_number,
                        학번: this.sid,
                        반: this.classValue,
                        위치: this.position
                    })
                    .then(response => {
                        this.message = response.data.data
                        if(response.data.status == 'success') {
                            location.href="/"
                        }
                    })

            }
        }
    }
</script>
