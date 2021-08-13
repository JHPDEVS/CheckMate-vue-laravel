<template>
    <div class='pb-1'>
        <div class="mx-10 flex justify-between h-16">
            <professor-button v-for="(navName, idx) in navList" :key="idx"
                @click.native="changeActive(idx+1)"
                :isActive="isActive" :myIdx="idx+1" :navName="navName"/>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'

    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

    import ProfessorButton from '@/Jetstream/ProfessorDashBoardHeadNavBtn'
    export default {
        components: {
            JetApplicationLogo,

            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetResponsiveNavLink,

            ProfessorButton,
        },
        data(){
            return {
                isActive:1,
                navList:[
                    '미등록 사용자', 'WDJ','CPJ','Professor'
                ],
            }
        },
        
        methods:{
            // isActive (menuItem) {
            //     return this.activeItem === menuItem
            // },
            // setActive (menuItem) {
            //     this.activeItem = menuItem
            // }
            changeActive(idx){
                this.isActive = idx;
                console.log(idx);
                this.$emit('changeTag',idx);
            }
        }
    }
</script>