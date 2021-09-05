<template>
    <app-layout>
        <div>
            <div class="max-w-7xl mx-auto py-3 sm:px-6 lg:px-8">
                    <update-profile-information-form />

                    <jet-section-border />

                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import DeleteUserForm from './DeleteUserForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import UpdatePasswordForm from './UpdatePasswordForm'
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'

    export default {
        props: ['sessions'],

        components: {
            AppLayout,
            DeleteUserForm,
            JetSectionBorder,
            LogoutOtherBrowserSessionsForm,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },
    }
</script>
