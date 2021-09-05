<template>
    <div>
        <modal
            :show="show"
            :max-width="maxWidth"
            :closeable="closeable"
            @close="attendClose"
        >
            <div class="py-4">
                <div class="px-6 text-2xl">
                    <slot name="title">
                        {{ this.user.name }}
                        <div class="flex flex-col mb-4"></div>
                    </slot>
                </div>
                <div class="mt-4">
                    <slot name="content">
                        <svg
                            v-if="user.current_team_id == '1'"
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            class="mx-6 w-8 h-8 text-red-400"
                        >
                            <path
                                d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            class="mx-6 w-8 h-8 text-gray-400"
                        >
                            <path
                                d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"
                            ></path>
                        </svg>
                        <table
                            v-if="ifLoading < 1"
                            class="
                                loading
                                flex
                                items-center
                                justify-center
                                min-h-full min-w-full
                                bg-white
                            "
                        >
                            <loading-bar />
                        </table>
                        <table
                            v-else
                            class="
                                min-w-full
                                table-auto
                                divide-y divide-gray-200
                            "
                        >
                            <thead class="bg-gray-50">
                                <tr class="flex">
                                    <th
                                        scope="col"
                                        class="
                                            w-1/10
                                            py-3
                                            text-center text-xs
                                            font-medium
                                            text-gray-500
                                            uppercase
                                            tracking-wider
                                        "
                                    >
                                        <span class="sr-only">Id</span>
                                    </th>
                                    <th
                                        scope="col"
                                        class="
                                            w-2/10
                                            py-3
                                            text-center text-xs
                                            font-medium
                                            text-gray-500
                                            uppercase
                                            tracking-wider
                                        "
                                    >
                                        상태
                                    </th>
                                    <th
                                        scope="col"
                                        class="
                                            w-2/10
                                            py-3
                                            text-center text-xs
                                            font-medium
                                            text-gray-500
                                            uppercase
                                            tracking-wider
                                        "
                                    >
                                        바퀴
                                    </th>
                                    <th
                                        scope="col"
                                        class="
                                            w-4/10
                                            py-3
                                            text-center text-xs
                                            font-medium
                                            text-gray-500
                                            uppercase
                                            tracking-wider
                                        "
                                    >
                                        시간
                                    </th>
                                    <th
                                        scope="col"
                                        class="
                                            w-1/10
                                            py-3
                                            text-center text-xs
                                            font-medium
                                            text-gray-500
                                            uppercase
                                            tracking-wider
                                        "
                                    >
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                v-if="ifLoading"
                                class="bg-white divide-y divide-gray-200"
                            >
                                <div
                                    v-if="
                                        Object.keys(this.attend.data).length !=
                                        0
                                    "
                                >
                                    <ProfessorAttendDialogTableBody
                                        v-for="(atd, idx) in this.attend.data"
                                        :key="idx"
                                        :attend="atd"
                                        :index="idx + 1"
                                        :bodytype="0"
                                        @attendClick="acceptClick"
                                    >
                                    </ProfessorAttendDialogTableBody>
                                    <div
                                        class="
                                            bg-white
                                            px-4
                                            py-3
                                            items-center
                                            justify-between
                                            border-t border-gray-200
                                            sm:px-6
                                            lg:flex
                                        "
                                    >
                                        <div
                                            class="
                                                flex-1 flex
                                                items-center
                                                justify-between
                                            "
                                        >
                                            <div>
                                                <nav
                                                    class="
                                                        relative
                                                        z-0
                                                        inline-flex
                                                        flex-nowrap
                                                        rounded-md
                                                        shadow-sm
                                                        -space-x-px
                                                    "
                                                    aria-label="Pagination"
                                                >
                                                    <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                                                    <a
                                                        v-for="(
                                                            item, i
                                                        ) in pageLinks"
                                                        v-bind:key="i"
                                                    >
                                                        <button
                                                            @click="
                                                                refreshByPage(
                                                                    item.label
                                                                )
                                                            "
                                                            v-if="
                                                                item.active ==
                                                                true
                                                            "
                                                            class="
                                                                z-10
                                                                bg-indigo-50
                                                                border-indigo-500
                                                                text-indigo-600
                                                                relative
                                                                inline-flex
                                                                items-center
                                                                px-4
                                                                py-2
                                                                border
                                                                text-sm
                                                                font-medium
                                                            "
                                                        >
                                                            {{ item.label }}
                                                        </button>
                                                        <button
                                                            @click="
                                                                refreshByPage(
                                                                    item.label
                                                                )
                                                            "
                                                            v-else
                                                            class="
                                                                bg-white
                                                                border-gray-300
                                                                text-gray-500
                                                                hover:bg-gray-50
                                                                relative
                                                                inline-flex
                                                                items-center
                                                                px-4
                                                                py-2
                                                                border
                                                                text-sm
                                                                font-mediums
                                                            "
                                                        >
                                                            {{ item.label }}
                                                        </button>
                                                    </a>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <tr class="flex">
                                        <td
                                            class="
                                                w-full
                                                whitespace-nowrap
                                                text-center
                                                py-3
                                            "
                                        >
                                            해당 학생은 출결 기록이 없습니다.
                                        </td>
                                    </tr>
                                </div>
                                <div v-if="this.attendRun.data[0]">
                                    <ProfessorAttendDialogTableBody
                                        v-if="this.attendRun.data[0].countRun"
                                        :attend="
                                            this.attendRun.data[0].countRun
                                        "
                                        :index="'달려야 할 바퀴'"
                                        :bodytype="1"
                                    >
                                    </ProfessorAttendDialogTableBody>
                                    <ProfessorAttendDialogTableBody
                                        v-if="this.attendRun.data[0].minusRun"
                                        :attend="
                                            this.attendRun.data[0].minusRun
                                        "
                                        :index="'달린 바퀴'"
                                        :bodytype="1"
                                    >
                                    </ProfessorAttendDialogTableBody>
                                    <ProfessorAttendDialogTableBody
                                        v-if="this.attendRun.data[0].totalRun"
                                        :attend="
                                            this.attendRun.data[0].totalRun
                                        "
                                        :index="'누적 바퀴'"
                                        :bodytype="1"
                                    >
                                    </ProfessorAttendDialogTableBody>
                                </div>
                                <div v-else>
                                    <tr class="flex">
                                        <td
                                            class="
                                                w-full
                                                whitespace-nowrap
                                                text-center
                                                py-3
                                            "
                                        >
                                            해당 학생은 달려야 할 바퀴수가
                                            없습니다.
                                        </td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </slot>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-100 text-right">
                <slot name="footer">
                    <jet-button @click.prevent="attendClose" class="mr-3">
                        Close
                    </jet-button>
                </slot>
            </div>
        </modal>
    </div>
</template>

<script>
import Modal from "@/Jetstream/Modal";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import ProfessorAttendDialogTableBody from "@/Pages/Professor/ProfessorAttendDialogTableBody";
import LoadingBar from "@/Pages/Board/LoadingBar";

export default {
    emits: ["attendClose", "acceptClick", "pageNation"],
    components: {
        Modal,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        ProfessorAttendDialogTableBody,
        LoadingBar,
    },
    props: {
        show: {
            default: false,
        },
        maxWidth: {
            default: "2xl",
        },
        closeable: {
            default: true,
        },
        user: Object,
        attend: Object,
        attendRun: Object,
        ifLoading: {
            default: 0,
        },
        currentPage: Number,
        pageLinks: Array,
        fake: 0,
    },
    data() {
        return {
            waiting: false,
        };
    },
    methods: {
        attendClose() {
            this.$emit("attendClose");
        },
        acceptClick(attend) {
            this.$emit("acceptClick", attend);
        },
        refreshByPage(page) {
            this.$emit("pageNation", page, this.user.id);
        },
    },
};
</script>

<style>
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<style>
.loading {
    position: absolute;
    height: 100%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
</style>
