<template>
    <app-layout>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                        role="alert">
        
                        <kakao />

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
        <Dialog :show="dialogShow" @close="closeDialog">

        <template #title>
            {{ msg }}
        </template>

        <template #content>
            {{header}}
            <br>
            {{ header2 }}
        </template>

        <template #footer>

            <jet-button type="button" class="ml-2">
                <inertia-link :href="route('setInfo')" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">확인</inertia-link>
            </jet-button>

        </template>
    </Dialog>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import kakao from '@/Geofencing/KaKaoMap'
    import Dialog from '@/Jetstream/DialogModal'
    export default {
         data: function () {
            return {
                header: '',
                header2: '',
                msg: '',
                user_name: document.head.querySelector('meta[name="user-name"]').content,
                user_sid: document.head.querySelector('meta[name="user-sid"]').content,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                dialogShow: false,
            }
        },
         mounted() {
             if(!this.user_sid) {
                this.msg = '에러!';
                this.header = '학번이 존재하지 않습니다';
                this.header2 = '확인을 누르면 추가정보 입력창으로 이동됩니다'
                this.openDialog();
             }
        },
        methods: {
            openDialog() {
                this.dialogShow= true;
            }
        },
        components: {
            AppLayout,
            kakao,
            Dialog,
        },


    }
</script>

<style>
jet-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>