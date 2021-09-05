<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'inline-flex items-center font-semibold px-1 pt-1 border-b-4 border-primary-content-400 text-sm font-medium leading-5 text-primary-content focus:outline-none focus:border-primary-content-700 transition'
                            : 'inline-flex items-center font-semibold px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-primary-content hover:border-primary-content focus:outline-none focus:text-primary-content-700 focus:border-primary-content transition'
            }
        }
    }
</script>
