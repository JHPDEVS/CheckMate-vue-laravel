<template>
  <div
    class="px-4 h-6 rounded-full text-xs font-semibold flex items-center cursor-pointer truncate"
    :class="`bg-green-100 text-green-700 hover:bg-green-500 hover:text-white`"
  >
   <i class="fas fa-check">    <slot></slot></i>
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
