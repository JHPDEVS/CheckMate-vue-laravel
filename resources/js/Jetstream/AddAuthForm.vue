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
                    <option selected="selected">WDJ</option>
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

                <select class="select select-bordered w-full max-w-xs" v-model="position">
                    <option selected="selected">학생</option>
                    <option selected="selected">교수</option>
                </select>
                <span v-if="position == '교수'"
                    class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">회원관리 기능은 운영자 확인 후
                    부여됩니다</span>

            </div>
            <button @click="save"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">제출</button>
            <input type="hidden" name="_token" :value="csrf">
        </form>
    </div>

    <Dialog :show="dialogShow" @close="closeDialog">



        <template #content>
            {{ header }}
        </template>

        <template #footer>

            <jet-button type="button"
                class="ml-2 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                @click="close">
                확인
            </jet-button>

        </template>
    </Dialog>
</template>

<script>
    import Input from './Input.vue'
    import axios from 'axios'
    import Dialog from '@/Jetstream/DialogModal'
    export default {
        data: function () {
            return {

                message: '',
                header: '',
                name: '',
                phone_number: '010',
                sid: '',
                classValue: 'WDJ',
                position: '학생',
                dialogShow: false,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
            }
        },
        components: {

            Input,
            Dialog
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
                        this.str = ''
                        this.message = response.data.data
                        if (response.data.status == 'success') {
                            location.href = "/"
                        }
                        console.log(response.data)
                        if(response.data.status == "학번"){
                            this.header = response.data.data
                            this.dialogShow = true
                        }
                    })

            },
            close() {
                this.dialogShow = false
            }
        }
    }
</script>
