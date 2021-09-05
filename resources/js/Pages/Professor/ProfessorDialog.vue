<template>
    <div>
        <modal
            :show="show"
            :max-width="maxWidth"
            :closeable="closeable"
            @close="close"
        >
            <form>
                <div class="px-3 py-4">
                    <div class="text-lg">
                        <slot name="title"> </slot>
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
                                class="w-8 h-8 text-red-400"
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
                                class="w-8 h-8 text-gray-400"
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
                            <div class="flex flex-col mb-4">
                                <jet-label for="name" value="Name" />
                                <jet-input
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user.name"
                                    autocomplete="name"
                                    placeholder="박 성철"
                                    @keydown.enter.prevent="change"
                                />
                            </div>
                            <div class="flex flex-col mb-4">
                                <jet-label for="email" value="Email" />
                                <jet-input
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="user.email"
                                    autocomplete="email"
                                    placeholder="scpark@g.yju.ac.kr"
                                    @keydown.enter.prevent="change"
                                />
                            </div>
                            <div class="flex flex-col mb-4">
                                <jet-label for="sid" value="Sid" />
                                <jet-input
                                    id="sid"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="user.sid"
                                    autocomplete="sid"
                                    placeholder="0000000"
                                    @keydown.enter.prevent="change"
                                />
                            </div>
                            <div class="flex flex-col mb-4">
                                <jet-label
                                    for="phone_number"
                                    value="Phone_number"
                                />
                                <jet-input
                                    id="phone_number"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="user.phone_number"
                                    autocomplete="phone_number"
                                    placeholder="018-0000-0000"
                                    @keydown.enter.prevent="change"
                                />
                            </div>
                            <div class="flex flex-col mb-4">
                                <jet-label
                                    for="current_team_id"
                                    value="Class"
                                />
                                <label
                                    v-for="(cname, idx) in classname"
                                    :key="idx"
                                >
                                    <input
                                        v-if="idx + 1 == user.current_team_id"
                                        type="radio"
                                        name="class"
                                        :value="idx + 1"
                                        checked
                                    />
                                    <input
                                        v-else
                                        type="radio"
                                        name="class"
                                        :value="idx + 1"
                                    />
                                    {{ cname }}
                                </label>
                            </div>
                        </slot>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <slot name="footer">
                        <jet-button
                            @click.prevent="close"
                            :disabled="waiting"
                            class="mr-3"
                        >
                            Close
                        </jet-button>
                        <jet-button @click.prevent="change" :disabled="waiting">
                            Save
                        </jet-button>
                    </slot>
                </div>
            </form>
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
import LoadingBar from "@/Pages/Board/LoadingBar";
import axios from "axios";

export default {
    emits: ["close"],
    components: {
        Modal,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
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
    },
    data() {
        return {
            classname: ["미등록 사용자", "WDJ", "CPJ", "교수"],
            waiting: false,
            ifLoading: 1,
        };
    },
    methods: {
        close() {
            this.$emit("close", 1, null);
        },
        change() {
            this.ifLoading = 0;
            this.waiting = true;
            const checked = document.querySelector(
                'input[name="class"]:checked'
            );
            const checkedNumber = Number(checked.value);
            if (!(checked && checkedNumber >= 1 && checkedNumber < 5)) {
                console.log("비정상적인 접근");
                document.querySelector('input[name="class"]').checked = true;
                checked = document.querySelector('input[name="class"]:checked');
            }
            this.user.current_team_id = checkedNumber;
            this.user.class = "미등록 사용자";
            switch (checkedNumber) {
                case 1:
                    this.user.class = "미등록 사용자";
                    break;
                case 2:
                    this.user.class = "WDJ";
                    break;
                case 3:
                    this.user.class = "CPJ";
                    break;
                case 4:
                    this.user.class = "교수";
                    break;
            }

            if (
                !this.user.name ||
                this.user.name == "" ||
                !this.user.email ||
                this.user.email == "" ||
                !this.user.class ||
                this.user.class == "" ||
                !this.user.current_team_id ||
                this.user.current_team_id == "" ||
                !this.user.sid ||
                this.user.sid == "" ||
                !this.user.phone_number ||
                this.user.phone_number == ""
            ) {
                let errMsg = [];
                if (!this.user.name || this.user.name == "") {
                    errMsg.push("이름을 입력하지 않았습니다.");
                }
                if (!this.user.email || this.user.email == "") {
                    errMsg.push("이메일을 입력하지 않았습니다.");
                }
                if (!this.user.class || this.user.class == "") {
                    errMsg.push("반을 입력하지 않았습니다. 어떻게 하셨나요?");
                }
                if (
                    !this.user.current_team_id ||
                    this.user.current_team_id == ""
                ) {
                    errMsg.push("반을 선택하지 않았습니다. 어떻게 하셨나요?");
                }
                if (!this.user.sid || this.user.sid == "") {
                    errMsg.push("학번을 입력하지 않았습니다.");
                }
                if (!this.user.phone_number || this.user.phone_number == "") {
                    errMsg.push("휴대폰 번호를 입력하지 않았습니다.");
                }
                this.waiting = false;
                this.ifLoading = 1;
                this.$emit("close", 3, errMsg);
                return;
            }
            const user_team_id = document.head.querySelector(
                'meta[name="user-current_team_id"]'
            ).content;
            const user_id = document.head.querySelector(
                'meta[name="user-id"]'
            ).content;
            axios
                .patch("/api/user/" + this.user.id, this.user)
                .then((res) => {
                    this.waiting = false;
                    this.ifLoading = 1;

                    if (
                        this.user.id == user_id &&
                        this.user.current_team_id != user_team_id
                    ) {
                        this.$emit("close", 4027, [
                            "변경하였습니다.",
                            "보안을 위해 홈으로 갑니다.",
                        ]);
                    } else {
                        this.$emit("close", 2, ["변경하였습니다."]);
                    }
                })
                .catch((err) => {
                    this.waiting = false;
                    this.ifLoading = 1;
                    this.$emit("close", 3, ["다시 입력 부탁드립니다."]);
                    console.log(err);
                });
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
    height: auto;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
</style>
