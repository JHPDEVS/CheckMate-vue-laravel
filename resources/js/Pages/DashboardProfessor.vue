<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    교수용 DashBoard
                </h2>
            </template>
            <div class="py-3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 pt-3 shadow-md"
                        role="alert">
                            <professor-dash-board-head @changeTag="changeList"/>
                            <div>
                                <div class="flex flex-col">
                                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                                <table class="min-w-full divide-y divide-gray-200 table-auto">
                                                    <thead class="bg-gray-50">
                                                        <tr class="justify-between flex">
                                                            <th scope="col" class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Name
                                                            </th>
                                                            <th scope="col" class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                <span>Sid</span>
                                                            </th>
                                                            <th scope="col" class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                <span>Edit</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <table v-if="ifLoading<1" class="flex items-center justify-center min-w-full bg-white">
                                                        <loading-bar />
                                                    </table>
                                                    <tbody>
                                                        <professor-dash-board v-for="user in selectedTeam" :key="user.id"
                                                        :user="user"
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
        

        <professor-dialog :show="dialogShow"
        :user="selectedUser" @close="closeDialog">
        </professor-dialog>
        
        <professor-error-dialog :show="errorDialogShow"
        :errMsg="errMsg" :errState="errState" @errorClose="closeErrorDialog">
        </professor-error-dialog>
    </div>
    
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import ProfessorDashBoardHead from '@/Jetstream/ProfessorDashBoardHead'
    import ProfessorDashBoard from '@/Jetstream/ProfessorDashBoard'
    import ProfessorDialog from '@/Jetstream/ProfessorDialog'
    import ProfessorErrorDialog from '@/Jetstream/ProfessorErrorDialog'
    import axios from 'axios'
    import LoadingBar from "@/Pages/Board/LoadingBar"

    export default {
        components: {
            AppLayout,
            ProfessorDashBoardHead,
            ProfessorDashBoard,
            ProfessorDialog,
            ProfessorErrorDialog,
            LoadingBar,
        },
        data(){
            return{
                dialogShow:false,
                errorDialogShow:false,
                selectedUser:{},
                backupUser:{
                    name:'',
                    email:'',
                    sid:'',
                    phone_number:'',
                    current_team_id:'',
                },
                selectedTeam:[],
                allTeam:[],
                form: this.$inertia.form({
                    _method: 'PATCH',
                    user:{},
                }),
                ifLoading:0,
                errMsg:[],
                errState:0,
            }
        },
        props: {
            users: Object,
        },
        methods:{
            closeDialog(state, errMsg){
                console.log('클로즈 도착');
                console.log('행위에 대한 결과: ',state)
                this.errMsg = errMsg;
                if(state==0){
                    return;
                }else if(state==1||state==3){
                    this.errState=1;
                    this.selectedUser.name = this.backupUser.name;
                    this.selectedUser.sid = this.backupUser.sid;
                    this.selectedUser.email = this.backupUser.email;
                    this.selectedUser.phone_number = this.backupUser.phone_number;
                    this.selectedUser.current_team_id = this.backupUser.current_team_id;
                    if(state==1){
                        this.dialogShow=false;
                        return;
                    }
                }else if(state==2){
                    this.errState=0;
                    for(let key in this.selectedTeam) {
                        if(this.selectedUser == this.selectedTeam[key]){
                            delete this.selectedTeam[key];
                            switch(this.selectedUser.current_team_id){
                                case "1":
                                    this.allTeam.none[key]=this.selectedUser;
                                    break;
                                case '2':
                                    this.allTeam.wdj[key]=this.selectedUser;
                                    break;
                                case '3':
                                    this.allTeam.cpj[key]=this.selectedUser;
                                    break;
                                case '4':
                                    this.allTeam.professor[key]=this.selectedUser;
                                    break;
                                default:
                                    loaction.reload();
                            }
                            break;
                        }
                    }
                }
                this.errorDialogShow=true;
                console.log('추가창 생성');
            },
            openDialog(user){
                console.log(user);
                console.log('오픈 도착');
                this.selectedUser=user;
                this.backupUser.name=user.name;
                this.backupUser.sid=user.sid;
                this.backupUser.email=user.email;
                this.backupUser.phone_number=user.phone_number;
                this.backupUser.current_team_id=user.current_team_id;
                this.dialogShow=true;
                // this.errorDialogShow=true;
            },
            closeErrorDialog(errState){
                console.log('추가 창 종료');
                if(errState==0){
                    this.dialogShow=false;
                }
                this.errorDialogShow=false;
            },
            changeList(tagIdx){
                console.log('Nav Tag 변경',tagIdx);
                switch(tagIdx){
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
            }
        },
        mounted(){
            
            axios.get('/api/users')
                .then(res=>{
                    console.log(res.data.status);
                    const classTeam = res.data.data;
                    this.allTeam = classTeam;
                    this.selectedTeam = classTeam.none;
                    this.ifLoading = 1;
                    console.log("유저 정보받아오기 성공")
                })
                .catch(err=>{
                    console.log(err);
                })
            console.log(this.allTeam);
        },
    }
</script>
