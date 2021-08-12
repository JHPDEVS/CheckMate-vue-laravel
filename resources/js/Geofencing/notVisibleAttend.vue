<template>
    <li class="bg-white shadow-lg p-4 rounded-lg flex justify-between w-full mb-3">
        <div class="flex items-center">
            <img class="w-12 h-12 rounded-full" :src="user_photo" :alt="user_name">
            <div class="ml-3">
                <p class="text-gray-900 font-semibold tracking-wide text-sm">{{user_name}}</p>
                <div class="flex text-sm">
                    <p class="font-semibold mr-1">{{user_sid}}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <badge-red class="m-1" value="abcense" @click="absence">결석</badge-red>
        </div>
    </li>

    <!-- 최근 3개의 출석 내역 뽑아옴 -->
    <li class="bg-white shadow-lg p-4 rounded-lg flex justify-between w-full mb-3">
        <div>
            <p class="text-gray-900 font-semibold tracking-wide text-sm">최근 출석</p>
            <div>
                <div v-if="isLoading==0">
                    <loading-bar />
                </div>
                <div v-else class="flex flex-wrap m-3 items-center">
                    <div v-for="(attend,index) in attends" v-bind:key="index" class="m-1">
                        <badge-red v-if="attend.desc_value=='결석'" @click="openAttendDialog(index)">{{ attend.attend }}
                        </badge-red>
                        <badge-yellow v-else-if="attend.desc_value=='지각'" @click="openAttendDialog(index)">
                            {{ attend.attend }}</badge-yellow>
                        <badge-green v-else @click="openAttendDialog(index)">{{ attend.attend }}</badge-green>
                    </div>
                    <badge-red v-if="attends.length == '+0'">출석정보 없음</badge-red>
                </div>
            </div>
        </div>
    </li>
    <Dialog :show="dialogShow" @close="closeDialog">

        <template #title>
            {{ msg }}
        </template>

        <template #content>
            {{header}}
        </template>

        <template #footer>

            <jet-button type="button" class="ml-2">
                <inertia-link :href="route('attend')"
                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                    확인</inertia-link>
            </jet-button>

        </template>
    </Dialog>

    <Dialog :show="showAttend" @close="closeDialog">

        <template #title>
            {{ msg }}
        </template>

        <template #content>
            {{header}}
            <br>
            {{header2}}
        </template>

        <template #footer>

            <jet-button type="button"
                class="ml-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                @click="closeDialog">
                닫기
            </jet-button>

        </template>
    </Dialog>
</template>

<script>
    import Dialog from '@/Jetstream/DialogModal'
    import BadgeRed from './BadgeRed'
    import BadgeGreen from './BadgeGreen'
    import BadgeYellow from './BadgeYellow'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    export default {
        data: function () {
            return {
                header: '',
                header2: '',
                msg: '',
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
            Dialog,
            BadgeRed,
            BadgeGreen,
            BadgeYellow,
            LoadingBar,
        },
        mounted() {

            axios.get('/api/user/attendance/' + this.user_id, null)
                .then(response => {
                    this.attends = response.data.attends;
                    this.isLoading = 1;
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {
            absence() {
                let value = {
                    user_sid: this.user_sid
                }
                axios.post('/api/absence', null, {
                        params: value
                    })
                    .then(response => {
                        console.log(response);
                        this.msg = response.statusText;
                        this.header = response.data.message;
                        this.openDialog();
                    })
                    .catch(response => {
                        console.log(response)
                        this.msg = '출석 실패';
                        this.header = '출석 서버와 통신에 실패했습니다';
                        this.openDialog();
                    })
            },
            openDialog() {
                this.dialogShow = true;
            },
            closeDialog() {
                this.showAttend = false;
            },
            openAttendDialog(index) {
                this.msg = this.attends[index].desc_value;
                if (this.attends[index].run) {
                    this.header = this.attends[index].attend + " : [" + this.attends[index].desc_value + "]입니다";
                    this.header2 = " 지각 패널티 : " + this.attends[index].run + "바퀴 ";
                } else {
                    this.header = this.attends[index].attend + " : [" + this.attends[index].desc_value + "]입니다";
                    this.header2 = ''
                }
                this.showAttend = true;
            }
        }
    }
</script>