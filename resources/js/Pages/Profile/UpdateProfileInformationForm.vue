<template>
    <jet-action-section>
        <template #title>
            프로필 정보
        </template>

        <template #description>
            이름을 수정 할 수 있습니다.
        </template>

        <template #content>
            <form action="/setInfo/save" method="post" v-on:submit.prevent>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 font-bold text-lg text-gray-900" for="name">이름</label>
                    <div class="form-control">
                        <input type="text" placeholder="홍길동" class="input input-success  input-bordered" v-model="name">
                        <span v-if="message.이름"
                            class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ message.이름 }}</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4">
                    <label class="mb-2 font-bold text-lg text-gray-900" for="phone_number">전화번호</label>
                    <div class="form-control">
                        <input type="number" placeholder="username" class="input input-success  input-bordered"
                            v-model="phone_number">
                        <span v-if="message.전화번호"
                            class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">{{ message.전화번호 }}</span>
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
                        <input type="text" disabled placeholder="1801234" class="input input-success  input-bordered"
                            v-model="sid">
                        <span
                            class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">학번은 변경이 불가능합니다. 관리자에게 문의해주세요</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4">
                    <label class="mb-2 font-bold text-lg text-gray-900" for="password">직업</label>

                    <select class="select select-bordered w-full max-w-xs" v-model="position">
                        <option selected="selected">학생</option>
                        <option selected="selected">교수</option>
                    </select>
                    <span v-if="position == '교수'"
                        class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">회원관리 기능은 운영자
                        확인 후
                        부여됩니다</span>

                </div>
                <button @click="save"
                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">제출</button>
                <input type="hidden" name="_token" :value="csrf">
            </form>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetInput from '@/Jetstream/Input'
    import axios from 'axios'
    import Dialog from '@/Jetstream/DialogModal'
    export default {
        data: function () {
            return {
                user_name: document.head.querySelector('meta[name="user-name"]').content,
                user_sid: document.head.querySelector('meta[name="user-sid"]').content,
                user_class: document.head.querySelector('meta[name="user-class"]').content,
                user_photo: document.head.querySelector('meta[name="user-photo"]').content,
                user_phone: document.head.querySelector('meta[name="user-phone"]').content,
                user_position: document.head.querySelector('meta[name="user-position"]').content,
                user_id: document.head.querySelector('meta[name="user-id"]').content,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                message: '',
                header: '',
                name: '',
                phone_number: '010',
                sid: '',
                classValue: 'WDJ',
                position: '학생',
                dialogShow: false,
            }
        },
        components: {
            JetActionSection,
            JetInput,
            Dialog
        },
        mounted() {
            this.name = this.user_name
            this.phone_number = this.user_phone
            this.classValue = this.user_class
            this.sid = this.user_sid
            this.position = this.user_position
        },

        methods: {
            save() {
                axios.post('/profile/save', {
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
                        if (response.data.status == "학번") {
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
