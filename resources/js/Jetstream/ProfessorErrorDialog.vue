<template>
    <div>
        <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="errorClose">
            <div class="px-3 py-4">
                <div class="text-lg">
                    <slot name="title">
                    </slot>
                </div>
                <div class="mt-4">
                    <slot name="content">
                        <div v-for="(err, idx) in errMsg" :key="idx"><span>{{err}}</span></div>
                    </slot>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-100 text-right">
                <slot name="footer">
                    <jet-button  @click.prevent="errorClose" class="mr-3">
                        Close
                    </jet-button>
                </slot>
            </div>
        </modal>
    </div>
</template>

<script>
    import Modal from './ProfessorErrorModal'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from './Button'

    export default {
        emits: ['errorClose'],
        components: {
            Modal,
            JetButton,
            JetInput,
            JetInputError,
            JetLabel,
            JetActionMessage,
        },
        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: 'xl'
            },
            closeable: {
                default: true
            },
            errMsg:{
                default: '에러데수'
            },
            errState:{
                default:0,
            }
        },
        methods: {
            errorClose() {
                console.log('에러창 닫기');
                if(this.errState==1){
                    this.$emit('errorClose',1);
                }else{
                    this.$emit('errorClose',0);
                }
                
            },
        },
    }
</script>