<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
            
        </template>

            <div class="auth">
                  <div class="rounded-t mb-0 px-6 py-6">
                        <div class="text-center mb-3">
                            <h6 class="text-gray-600 text-sm font-bold">체크메이트 로그인</h6></div>
                            <hr class="mt-2 border-b-1 border-gray-400">
                        <div class="flex items-center mt-6">
                            <img alt="..." @click="goToKakao" class="w-full hover:opacity-50" src="/img/kakao_login_medium_wide.png">
                        </div>
                    </div>
                
    </div>
    
    </jet-authentication-card>

</template>

<script>

    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false,
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            },

            goToKakao() {
                location.href="/login/kakao"
            }
        }
    }
</script>
