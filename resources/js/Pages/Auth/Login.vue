<template>
<div class="relative min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 bg-gray-500 bg-no-repeat bg-cover relative items-center"
	style="background-image: url(/img/main.png);">
	<div class="absolute bg-black opacity-60 inset-0 z-0"></div>
	<div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl z-10">
		<div class="text-center">
            <lottie-animation
    path="animation/72719-check.json" loop="false"
/>
            <h2 id="apptitle" class="text-3xl animate"><i class="fas fa-check"></i>체크메이트</h2>
                      <div class="flex items-center justify-center space-x-2">
            <span class="h-px w-48 bg-gray-200"></span>
          </div>
			<h2 class="mt-6 text-3xl font-bold text-gray-900">
				환영합니다!
			</h2>
			<p class="mt-2 text-sm text-gray-600">체크메이트에 로그인하여 출석하세요!</p>
		</div>
		<div class="flex flex-row justify-center items-center space-x-3">
	                <button @click="goToKakao"
                  class="lg:w-3/5 w-full flex justify-center btn  rounded-full tracking-wide font-bold  shadow-lg cursor-pointer transition ease-in duration-300"
                >
                  <i class="fas fa-comment fa-lg">카카오 로그인</i>
                </button>
		</div>
	</div>
</div>


</template>

<script>

    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue"; // import lottie-vuejs
    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            LottieAnimation,
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

<style>
#apptitle {
  font-family: 'Black Han Sans', sans-serif;
}
</style>