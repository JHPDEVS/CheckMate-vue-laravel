<template>
    <a href="" @click.prevent="openThread"  :class="classes" >
        <span class="flex flex-nowrap">
            {{this.navName}}
        </span>
    </a>
</template>

<script>
export default ({
    props:{
        navName:String,
        isActive:Number,
        myIdx:Number,
    },
    computed: {
        classes() {
            return this.myIdx==this.isActive
                ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition'
                : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
        }
    },
})
</script>
