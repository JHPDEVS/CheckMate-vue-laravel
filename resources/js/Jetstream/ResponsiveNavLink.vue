<template>
    <div>
        <button :class="classes" class="w-full text-left " v-if="as == 'button'">
            <slot></slot>
        </button>

        <inertia-link :href="href" :class="classes" v-else>
              <span class="block px-1 pt-1 pb-1">
                                        <i :class="home" class="fa text-2xl pt-1 mb-1 block"></i>
                                        <span class="block text-xs pb-2">{{ name }}</span>
                                       <span
                                  :class="classes2"></span>
                   </span>
        </inertia-link>
    </div>
</template>

<script>
    export default {
        props: ['active', 'href', 'as','home','notice','attend','timetable','attendstatus','runauth','profileshow'],

        computed: {
            classes() {
                return this.active
                            ? 'flex items-end justify-center text-center mx-auto pt-2 w-full text-indigo-500'
                            : 'flex items-end justify-center text-center mx-auto pt-2 w-full text-gray-400 focus:text-indigo-500 rounded-full'
            },

            classes2() {
                return this.active
                        ?  'block w-5 mx-auto h-1 bg-indigo-500 group-hover:bg-indigo-500 rounded-full'
                        :  'block w-5 mx-auto h-1  rounded-full focus:bg-indigo-500 rounded-full hover:bg-indigo-500 rounded-full'
            },
            home() {
                return this.home ? 'fa-home' :  this.notice ? 'fa-clipboard' : this.attend ? 'fa-check' : this.timetable ? 'fa-table' : this.attendstatus ? 'fa-calendar-check' : this.runauth ? 'fa-running' : this.profileshow ? 'fa-id-card' : 'fa-columns'
            },
            name() {
                return this.home ? '홈' : this.notice ? '게시판' : this.attend ? '출석' :this.timetable ? '시간표' : this.attendstatus ? '출석현황' : this.runauth ? '달리기 인증' : this.profileshow ? '프로필 수정'  : '교수용 대시보드'
            }
        }
    }
</script>
