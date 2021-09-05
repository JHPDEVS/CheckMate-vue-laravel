<template>
    <tr v-if="type == 0" class="flex">
        <td class="w-1/4 py-4 whitespace-nowrap text-center">
            <div class="text-center inline">
                <span
                    v-if="user.name"
                    class="text-sm text-center text-gray-900"
                >
                    {{ user.name }}
                </span>
                <span
                    v-else
                    class="
                        text-center
                        inline-flex
                        text-xs
                        leading-5
                        font-semibold
                        rounded-full
                        bg-red-100
                        text-red-800
                    "
                >
                    등록된 이름 없음
                </span>
            </div>
        </td>
        <td class="w-1/4 py-4 whitespace-nowrap text-center">
            <div class="text-center inline">
                <span v-if="user.sid" class="text-sm text-center text-gray-900">
                    {{ user.sid }}
                </span>
                <span
                    v-else
                    class="
                        text-center
                        inline-flex
                        text-xs
                        leading-5
                        font-semibold
                        rounded-full
                        bg-red-100
                        text-red-800
                    "
                >
                    등록된 학번 없음
                </span>
            </div>
        </td>
        <td class="w-1/4 py-4 whitespace-nowrap text-center">
            <div class="text-center inline">
                <span v-if="user.sid" class="text-sm text-center text-gray-900">
                    {{ user.class }}
                </span>
                <span
                    v-else
                    class="
                        text-center
                        inline-flex
                        text-xs
                        leading-5
                        font-semibold
                        rounded-full
                        bg-red-100
                        text-red-800
                    "
                >
                    등록된 반 없음
                </span>
            </div>
        </td>
        <td class="w-1/4 py-4 whitespace-nowrap text-right font-medium">
            <div class="text-center">
                <a
                    href=""
                    @click="open"
                    @click.prevent="openThread"
                    class="text-sm text-indigo-600 hover:text-indigo-900 p-3"
                    >수정</a
                >
            </div>
        </td>
    </tr>
    <tr v-else class="flex">
        <td class="py-4 w-full whitespace-nowrap text-center">
            <div class="text-center inline">
                <span class="text-sm text-center text-gray-900">
                    해당 반에 등록된 유저가 없습니다.
                </span>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    components: {},
    props: {
        type: Number,
        user: Object,
    },
    data() {
        return {
            dialog: false,
        };
    },
    methods: {
        open() {
            this.$emit("open", this.user);
        },
    },
};
</script>
