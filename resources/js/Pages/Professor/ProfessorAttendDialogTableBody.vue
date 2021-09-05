<template>
    <tr v-if="bodytype == 0" class="flex py-3">
        <td class="w-1/10 whitespace-nowrap text-center text-xs font-medium">
            {{ index }}
        </td>
        <td class="w-2/10 whitespace-nowrap text-center text-xs font-medium">
            {{ attend.desc_value }}
        </td>
        <td class="w-2/10 whitespace-nowrap text-center text-xs font-medium">
            {{ attend.run }}
        </td>
        <td class="w-2/5 whitespace-nowrap text-center text-xs font-medium">
            {{ attend.attend }}
        </td>
        <td
            @click.prevent="openAttendChange"
            class="w-1/10 whitespace-nowrap text-left text-xs font-medium"
        >
            <a href="" class="text-sm text-indigo-600 hover:text-indigo-900"
                >수정</a
            >
        </td>
    </tr>
    <tr v-else class="flex">
        <td class="w-2/5 whitespace-nowrap text-right font-medium">
            {{ index }}
        </td>
        <td class="w-3/5 whitespace-nowrap text-center">
            {{ attend }}
        </td>
    </tr>
</template>

<script>
export default {
    components: {},
    props: {
        attend: {
            default: Object,
        },
        index: {
            default: 0,
        },
        bodytype: {
            default: 0,
        },
    },
    data() {
        return {};
    },
    methods: {
        openAttendChange() {
            this.$emit("attendClick", this.attend);
        },
    },
};
</script>
