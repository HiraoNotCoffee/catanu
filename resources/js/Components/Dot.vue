<script setup>

defineProps({
    dot: Array,
});

</script>
<template>

<div class="dot" :class="'dot-' + parseInt(dot.pos_id +  1)"></div>
</template>