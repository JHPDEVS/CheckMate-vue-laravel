<template>
  <div
    class="px-4 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
    :class="`bg-red-100 text-red-700 hover:bg-red-500 hover:text-white`"
  >
    <i class="fas fa-times-circle">    <slot></slot></i>
  </div>
</template>
<script>
export default {
  props: {
    color: {
      type: String,
      default: "teal"
    },
    showDot: {
      type: Boolean,
      default: true
    }
  }
};
</script>
