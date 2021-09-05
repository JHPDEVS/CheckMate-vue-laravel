<template>
    <div class="pb-1">
        <div class="mx-10 flex justify-between h-16">
            <professor-button
                v-for="(navName, idx) in navList"
                :key="idx"
                @click.native="changeActive(idx + 1)"
                :isActive="isActive"
                :myIdx="idx + 1"
                :navName="navName"
            />
        </div>
    </div>
</template>

<script>
import ProfessorButton from "@/Pages/Professor/ProfessorDashBoardHeadNavBtn";
export default {
    emits: ["changeTag", "wait"],
    components: {
        ProfessorButton,
    },
    props: {
        ifLoading: Number,
    },
    data() {
        return {
            isActive: 1,
            navList: ["미등록 사용자", "WDJ", "CPJ", "Professor"],
        };
    },

    methods: {
        changeActive(idx) {
            if (this.ifLoading < 1) {
                this.$emit("wait");
                return;
            }
            this.isActive = idx;
            this.$emit("changeTag", idx);
        },
    },
};
</script>
