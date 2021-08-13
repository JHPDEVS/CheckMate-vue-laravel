<template>
        <tr class="flex">
            <td class=" w-1/3 px-6 py-4 whitespace-nowrap text-center">
                <div class="max-w-max min-w-max text-center inline">
                    <span v-if="user.name" class="text-sm text-center text-gray-900">
                        {{user.name}}
                    </span>
                    <span v-else class="px-2 flex text-center inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        등록된 이름 없음
                    </span>
                </div>
            </td>
            <td class="w-1/3 px-6 py-4 whitespace-nowrap text-center">
                <div class="max-w-max min-w-max text-center inline">
                    <span v-if="user.sid" class="text-sm text-center text-gray-900">
                        {{user.sid}}
                    </span>
                    <span v-else class="px-2 text-center inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        등록된 학번 없음
                    </span>
                </div>
            </td>
            <td class="w-1/3 px-6 py-4 whitespace-nowrap text-right font-medium">
                <div class="text-center">
                    <a href="" @click="open" @click.prevent="openThread" class="text-sm text-indigo-600 hover:text-indigo-900 p-3">수정</a>
                </div>

            </td>
        </tr>
    <!-- </div> -->
</template>

<script>
    export default {
        components: {
        },
        props:{

            user:Object,
        },
        data(){
            return{
                dialog:false,
            }
        },
        methods:{
            open() {
                console.log('사용자 정보 열람');
                this.$emit('open',this.user)
            },
        }
    }
</script>
