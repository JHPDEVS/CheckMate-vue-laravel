<template>
    <div>
        <app-layout>
            <template #header>
                <div class="pb-1">
                    <div class="mx-10 flex justify-between h-16">
                        <professor-button
                            v-for="(navName, idx) in headList"
                            :key="idx"
                            @click.native="changeActive(idx + 1)"
                            :isActive="isModActive"
                            :myIdx="idx + 1"
                            :navName="navName"
                        />
                    </div>
                </div>
            </template>
            <div class="py-3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <div
                            class="
                                bg-teal-100
                                border-t-4 border-teal-500
                                rounded-b
                                text-teal-900
                                pt-3
                                shadow-md
                            "
                            role="alert"
                        >
                            <professor-dash-board-head
                                :ifLoading="ifLoading"
                                @wait="wait"
                                @changeTag="changeList"
                            />
                            <div>
                                <div class="flex flex-col">
                                    <div
                                        class="
                                            -my-2
                                            overflow-x-auto
                                            sm:-mx-6
                                            lg:-mx-8
                                        "
                                    >
                                        <div
                                            class="
                                                py-2
                                                align-middle
                                                inline-block
                                                min-w-full
                                                sm:px-6
                                                lg:px-8
                                            "
                                        >
                                            <div
                                                class="
                                                    shadow
                                                    overflow-hidden
                                                    border-b border-gray-200
                                                    sm:rounded-lg
                                                "
                                            >
                                                <table
                                                    class="
                                                        min-w-full
                                                        divide-y divide-gray-200
                                                    "
                                                >
                                                    <thead class="bg-gray-50">
                                                        <tr
                                                            class="
                                                                justify-between
                                                                flex
                                                            "
                                                        >
                                                            <th
                                                                scope="col"
                                                                class="
                                                                    w-1/4
                                                                    py-3
                                                                    text-center
                                                                    text-xs
                                                                    font-medium
                                                                    text-gray-500
                                                                    uppercase
                                                                    tracking-wider
                                                                "
                                                            >
                                                                Name
                                                            </th>
                                                            <th
                                                                scope="col"
                                                                class="
                                                                    w-1/4
                                                                    py-3
                                                                    text-center
                                                                    text-xs
                                                                    font-medium
                                                                    text-gray-500
                                                                    uppercase
                                                                    tracking-wider
                                                                "
                                                            >
                                                                Sid
                                                            </th>
                                                            <th
                                                                scope="col"
                                                                class="
                                                                    w-1/4
                                                                    py-3
                                                                    text-center
                                                                    text-xs
                                                                    font-medium
                                                                    text-gray-500
                                                                    uppercase
                                                                    tracking-wider
                                                                "
                                                            >
                                                                Class
                                                            </th>
                                                            <th
                                                                scope="col"
                                                                class="
                                                                    w-1/4
                                                                    py-3
                                                                    text-center
                                                                    text-xs
                                                                    font-medium
                                                                    text-gray-500
                                                                    uppercase
                                                                    tracking-wider
                                                                "
                                                            >
                                                                <span
                                                                    class="
                                                                        sr-only
                                                                    "
                                                                    >Edit</span
                                                                >
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <table
                                                        v-if="ifLoading < 1"
                                                        class="
                                                            flex
                                                            items-center
                                                            justify-center
                                                            min-w-full
                                                            bg-white
                                                        "
                                                    >
                                                        <loading-bar />
                                                    </table>
                                                    <tbody
                                                        v-if="ifLoading"
                                                        class="
                                                            bg-white
                                                            divide-y
                                                            divide-gray-200
                                                        "
                                                    >
                                                        <professor-dash-board
                                                            v-for="(
                                                                user, idx
                                                            ) in selectedTeam"
                                                            :key="idx"
                                                            :user="user"
                                                            :type="0"
                                                            @open="openDialog"
                                                        />
                                                        <professor-dash-board
                                                            v-if="
                                                                Object.keys(
                                                                    selectedTeam
                                                                ).length == 0
                                                            "
                                                            :user="{}"
                                                            :type="1"
                                                            @open="openDialog"
                                                        />
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </app-layout>
        <professor-dialog
            :show="dialogShow"
            :user="selectedUser"
            @close="closeDialog"
        >
        </professor-dialog>

        <professor-attend-dialog
            :show="attendDialogShow"
            :ifLoading="attendLoading"
            :user="selectedUser"
            :attend="selectedUserAttend"
            :attendRun="selectedUserRun"
            :currentPage="currentPage"
            :pageLinks="pageLinks"
            @attendClose="closeAttendDialog"
            @acceptClick="acceptAttend"
            @pageNation="refreshByPage"
        >
        </professor-attend-dialog>

        <professor-attend-change-dialog
            :show="attendChangeDialogShow"
            :user="selectedUser"
            :attend="selectedChangeAttend"
            :backupAttend="backupAttend"
            :ifLoading="attendLoading"
            :waiting="atdWaiting"
            @attendChangeClose="closeDialog"
            @attendChange="attendChange"
        >
        </professor-attend-change-dialog>

        <professor-error-dialog
            :show="errorDialogShow"
            :errMsg="errMsg"
            :errState="errState"
            @errorClose="closeErrorDialog"
        >
        </professor-error-dialog>
    </div>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ProfessorDashBoardHead from "@/Pages/Professor/ProfessorDashBoardHead";
import ProfessorDashBoard from "@/Pages/Professor/ProfessorDashBoardBody";
import ProfessorDialog from "@/Pages/Professor/ProfessorDialog";
import ProfessorErrorDialog from "@/Pages/Professor/ProfessorErrorDialog";
import ProfessorButton from "@/Pages/Professor/ProfessorDashBoardHeadNavBtn";
import ProfessorAttendDialog from "@/Pages/Professor/ProfessorAttendDialog";
import ProfessorAttendChangeDialog from "@/Pages/Professor/ProfessorAttendChangeDialog";
import axios from "axios";
import LoadingBar from "@/Pages/Board/LoadingBar";

export default {
    components: {
        AppLayout,
        ProfessorDashBoardHead,
        ProfessorDashBoard,
        ProfessorDialog,
        ProfessorErrorDialog,
        ProfessorButton,
        ProfessorAttendDialog,
        ProfessorAttendChangeDialog,
        LoadingBar,
    },
    data() {
        return {
            current_user: document.head.querySelector(
                'meta[name="user-current_team_id"]'
            ).content,
            dialogShow: false,
            attendDialogShow: false,
            attendChangeDialogShow: false,
            errorDialogShow: false,
            backupUser: {
                name: "",
                email: "",
                sid: "",
                phone_number: "",
                current_team_id: "",
            },
            backupAttend: {
                desc_value: "",
                run: "",
            },
            selectedTeam: {},
            selectedUser: {},
            selectedUserAttend: {},
            selectedUserRun: {},
            selectedChangeAttend: {},
            allTeam: {},
            form: this.$inertia.form({
                _method: "PATCH",
                user: {},
            }),
            ifLoading: 0,
            errMsg: [],
            errState: 0,
            headList: ["학생 정보관리", "출결관리"],
            attendLoading: 0, // attend loading
            atdWaiting: false,
            currentPage: 1,
            pageLinks: [],
            isModActive: 1,
            mod: 1,
        };
    },
    props: {
        users: Object,
    },
    methods: {
        wait() {
            this.errMsg = ["로딩중입니다"];
            this.errState0 = 0;
            this.errorDialogShow = true;
        },
        closeDialog(state, errMsg) {
            this.errMsg = errMsg;
            if (state == 0) {
                this.attendChangeDialogShow = false;
                return;
            } else if (state == 1 || state == 3) {
                this.errState = 1;
                this.selectedUser.name = this.backupUser.name;
                this.selectedUser.sid = this.backupUser.sid;
                this.selectedUser.email = this.backupUser.email;
                this.selectedUser.phone_number = this.backupUser.phone_number;
                this.selectedUser.current_team_id =
                    this.backupUser.current_team_id;
                if (state == 1) {
                    this.dialogShow = false;
                    return;
                }
            } else if (state == 2) {
                this.errState = 0;
                for (let key in this.selectedTeam) {
                    if (this.selectedUser == this.selectedTeam[key]) {
                        delete this.selectedTeam[key];
                        switch (this.selectedUser.current_team_id) {
                            case 1:
                                if (Object.keys(this.allTeam.none).length == 0)
                                    this.allTeam.none = {};
                                this.allTeam.none[key] = this.selectedUser;
                                // this.changeList(1);
                                break;
                            case 2:
                                if (Object.keys(this.allTeam.wdj).length == 0)
                                    this.allTeam.wdj = {};
                                this.allTeam.wdj[key] = this.selectedUser;
                                // this.changeList(2);
                                break;
                            case 3:
                                if (Object.keys(this.allTeam.cpj).length == 0)
                                    this.allTeam.cpj = {};
                                this.allTeam.cpj[key] = this.selectedUser;
                                // this.changeList(3);
                                break;
                            case 4:
                                if (
                                    Object.keys(this.allTeam.professor)
                                        .length == 0
                                )
                                    this.allTeam.professor = {};
                                this.allTeam.professor[key] = this.selectedUser;
                                // this.changeList(4);
                                break;
                            default:
                                loaction.reload();
                        }
                        break;
                    }
                }
            } else if (state == 4) {
                console.log(this.selectedUserRun);
                console.log(this.selectedChangeAttend);
                console.log(this.backupAttend);
                if (
                    Number(this.selectedChangeAttend.run) !=
                    Number(this.backupAttend.run)
                ) {
                    this.selectedUserRun.data[0].countRun -=
                        this.backupAttend.run;
                    this.selectedUserRun.data[0].totalRun -=
                        this.backupAttend.run;
                }
                this.attendLoading = 1;
                this.atdWaiting = false;
                this.errState = 0;
            } else if (state == 5 || state == 6) {
                this.errState = 1;
                this.selectedChangeAttend.desc_value =
                    this.backupAttend.desc_value;
                this.selectedChangeAttend.run = this.backupAttend.run;
                if (state == 6) {
                    this.attendChangeDialogShow = false;
                    return;
                } else {
                    this.errorDialogShow = true;
                }
            } else if (state == 4027) {
                this.errState = 4027;
            }
            this.errorDialogShow = true;
        },
        findEmptyTeam(team) {
            if (Object.keys(team).length == 0) {
                team = {};
                this.selectedTeam = team;
            }
        },
        openDialog(user) {
            this.selectedUser = user;
            if (this.mod == 1) {
                this.backupUser.name = user.name;
                this.backupUser.sid = user.sid;
                this.backupUser.email = user.email;
                this.backupUser.phone_number = user.phone_number;
                this.backupUser.current_team_id = user.current_team_id;
                this.dialogShow = true;
            } else if (this.mod == 2) {
                this.attendLoading = 0;
                this.attendDialogShow = true;
                axios
                    .get(
                        "/api/user/attendStatus/" +
                            user.id +
                            "?page=" +
                            this.currentPage +
                            "&attend=전체",
                        null
                    )
                    .then((res) => {
                        this.selectedUserAttend = res.data.user_attend;
                        this.selectedUserRun = res.data.user_run;

                        console.log(res.data);
                        this.currentPage = res.data.user_attend.current_page;
                        this.pageLinks = res.data.user_attend.links;
                        this.attendLoading = 1;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        closeErrorDialog(errState) {
            if (errState == 0 || errState == 4) {
                this.dialogShow = false;
                this.attendChangeDialogShow = false;
            }
            this.errorDialogShow = false;
            if (errState == 4027) {
                location.replace("/");
            }
        },
        changeList(tagIdx) {
            switch (tagIdx) {
                case 1:
                    this.selectedTeam = this.allTeam.none;
                    break;
                case 2:
                    this.selectedTeam = this.allTeam.wdj;
                    break;
                case 3:
                    this.selectedTeam = this.allTeam.cpj;
                    break;
                case 4:
                    this.selectedTeam = this.allTeam.professor;
                    break;
            }
        },
        closeAttendDialog() {
            this.currentPage = 1;
            this.attendDialogShow = false;
        },
        changeActive(idx) {
            this.isModActive = idx;
            this.mod = idx;
        },
        acceptAttend(attend) {
            this.attendChangeDialogShow = true;
            this.selectedChangeAttend = attend;
            this.backupAttend.desc_value = attend.desc_value;
            this.backupAttend.run = attend.run;
        },
        attendChange(attend, backupAttend, userid) {
            this.atdWaiting = true;
            this.attendLoading = 0;
            let errMsg = [];
            if (
                !attend.desc_value ||
                attend.desc_value == "" ||
                !(
                    attend.run == 0 ||
                    attend.run == "0" ||
                    Number(attend.run) == Number(backupAttend.run)
                )
            ) {
                if (!attend.desc_value || attend.desc_value == "") {
                    errMsg.push("상태를 입력하지 않았습니다.");
                }
                if (
                    (!attend.run || attend.run == "") &&
                    Number(attend.run) != 0
                ) {
                    errMsg.push("바퀴수를 입력하지 않았습니다.");
                }
                if (
                    !(
                        attend.run == 0 ||
                        attend.run == "0" ||
                        Number(attend.run) == Number(backupAttend.run)
                    )
                ) {
                    errMsg.push("바퀴수는 현재와 같거나 0이어야 합니다.");
                }
                this.atdWaiting = false;
                this.attendLoading = 1;
                this.closeDialog(5, errMsg);
                return;
            }
            errMsg.push("변경하였습니다.");
            axios
                .patch("/api/attends/" + userid, attend)
                .then(() => {
                    this.closeDialog(4, errMsg);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        refreshByPage(page, userId) {
            this.attendLoading = 0;
            if (page == "<") {
                if (this.pageLinks[0].url) {
                    page = this.pageLinks[0].url.charAt(
                        this.pageLinks[0].url.length - 1
                    );
                } else {
                    page = 1;
                }
            }
            if (page == ">") {
                console.log("여기요 여기");
                if (this.pageLinks[this.selectedUserAttend.last_page + 1].url) {
                    page = this.pageLinks[
                        this.selectedUserAttend.last_page + 1
                    ].url.charAt(
                        this.pageLinks[this.selectedUserAttend.last_page + 1]
                            .url.length - 1
                    );
                } else {
                    page = this.selectedUserAttend.last_page;
                }
            }
            console.log("전부 통과");
            axios
                .get(
                    "/api/user/attendStatus/" +
                        userId +
                        "?page=" +
                        page +
                        "&attend=전체",
                    null
                )
                .then((res) => {
                    this.selectedUserAttend = res.data.user_attend;
                    console.log(res.data);
                    this.currentPage = res.data.user_attend.current_page;
                    this.pageLinks = res.data.user_attend.links;
                    this.attendLoading = 1;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    mounted() {
        if (!(this.current_user == 4)) {
            this.errState = 4027;
            this.errMsg = ["볼 권한이 없습니다. 홈으로 돌아갑니다."];
            this.errorDialogShow = true;
            return;
        }
        axios
            .get("/api/users")
            .then((res) => {
                const classTeam = res.data.data;
                this.allTeam = classTeam;
                this.selectedTeam = this.allTeam.none;
                this.ifLoading = 1;
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
</script>
