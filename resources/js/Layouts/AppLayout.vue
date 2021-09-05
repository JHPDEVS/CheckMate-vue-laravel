<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-primary-100">
            <nav class=" mb-2 shadow-lg bg-primary text-primary-content">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <inertia-link :href="route('dashboard')">
                                    <span class="text-lg font-bold">
                                        체크메이트
                                    </span>
                                </inertia-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                                <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                    대시보드
                                </jet-nav-link>
                                <jet-nav-link :href="route('notice')" :active="route().current('notice')">
                                    게시판
                                </jet-nav-link>
                                <jet-nav-link :href="route('attend')" :active="route().current('attend')">
                                    출석
                                </jet-nav-link>
                                <jet-nav-link :href="route('attendstatus')" :active="route().current('attendstatus') || route().current('classAttendStatus')">
                                    출석현황
                                </jet-nav-link>
                                <jet-nav-link :href="route('runauth')" :active="route().current('runauth')">
                                    달리기 인증
                                </jet-nav-link>
                                <jet-nav-link :href="route('timetable')" :active="route().current('timetable')">
                                    시간표
                                </jet-nav-link>
                                <jet-nav-link v-if="$page.props.user &&$page.props.user.current_team_id==4"
                                    :href="route('dashboard.professor')"
                                    :active="route().current('dashboard.professor')">
                                    교수용 대시보드
                                </jet-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <!-- Teams Dropdown -->
                                <jet-dropdown align="right" width="80" v-if="$page.props.jetstream.hasTeamFeatures"
                                    class="text-primary-content">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" v-if="$page.props.user"
                                                class="badge badge-lg badge-accent badge-outline text-primary-content">
                                                {{ $page.props.user.current_team.name }}

                                            </button>
                                            <button type="button" v-else
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                                손님

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                   
                                </jet-dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos && $page.props.user"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline focus:border-gray-300 transition ">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                :src="$page.props.user.profile_photo_path"
                                                :alt="$page.props.user.name" />

                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" v-if="$page.props.user"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            계정 관리
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            프로필 수정
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('api-tokens.index')"
                                            v-if="$page.props.jetstream.hasApiFeatures">
                                            API Tokens
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                로그아웃
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>


                <!-- 모바일 -->
                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
                    class="sm:hidden bg-white">
                    <div class="px-7 bg-white shadow-lg rounded-2xl">
                        <div class="flex">
                            <div class="flex-1 group">
                                <jet-responsive-nav-link :href="route('dashboard')"
                                    :active="route().current('dashboard')" :home="route('dashboard')">

                                </jet-responsive-nav-link>
                            </div>
                            <div class="flex-1 group">
                                <jet-responsive-nav-link :href="route('notice')" :active="route().current('notice')"
                                    :notice="route('dashboard')">
                                </jet-responsive-nav-link>
                            </div>
                            <div class="flex-1 group">
                                <jet-responsive-nav-link :href="route('attend')" :active="route().current('attend')"
                                    :attend="route('dashboard')">
                                </jet-responsive-nav-link>
                            </div>
                            <div class="flex-1 group">
                                <jet-responsive-nav-link :href="route('attendstatus')"
                                    :active="route().current('attendstatus') || route().current('classAttendStatus')"  :attendstatus="route('attendstatus')">
                                </jet-responsive-nav-link>
                            </div>
                        </div>
                        <div class="flex">
                        <div class="flex-1 group">
                        <jet-responsive-nav-link :href="route('runauth')" :active="route().current('runauth')"
                            :runauth="route('runauth')">
                        </jet-responsive-nav-link>
                        </div>
                        <div class="flex-1 group">
                        <jet-responsive-nav-link :href="route('timetable')" :active="route().current('timetable')"
                            :timetable="route('dashboard')">
                        </jet-responsive-nav-link>
                        </div>
                        <div class="flex-1 group">
                        <jet-responsive-nav-link v-if="$page.props.user && $page.props.user.current_team_id==4"
                            :href="route('dashboard.professor')" :active="route().current('dashboard.professor')">
                            교수용 대시보드
                        </jet-responsive-nav-link>
                        </div>
                        </div>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-primary">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3">
                                <img v-if="$page.props.user" class="h-10 w-10 rounded-full object-cover"
                                    :src="$page.props.user.profile_photo_path" :alt="$page.props.user.name" />
                                <img v-else alt="손님" />
                            </div>

                            <div>
                                <div v-if="$page.props.user" class="font-medium text-base text-primary">
                                    {{ $page.props.user.name }}</div>
                                <div v-else class="font-medium text-base text-gray-800">게스트</div>
                                <div v-if="$page.props.user" class="font-medium text-sm text-gray-700">
                                    {{ $page.props.user.email }}</div>
                                <div v-else class="font-medium text-sm text-gray-500"></div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-1">

                            <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')" :profileshow="route('dashboard')">
                                            프로필 수정
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link v-if="!$page.props.user" :href="route('login')"
                                :active="route().current('login')">
                                로그인
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')"
                                v-if="$page.props.jetstream.hasApiFeatures">
                                API Tokens
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link v-if="$page.props.user" as="button">
                                    로그아웃
                                </jet-responsive-nav-link>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures && $page.props.user">
                                <div class="border-t border-primary"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    소속 그룹
                                </div>
                                <div class="flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div v-if="$page.props.user" class="text-primary">
                                        {{ $page.props.user.current_team.name }}</div>

                                </div>

                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl flex mx-auto  px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

    export default {
        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    }
</script>
