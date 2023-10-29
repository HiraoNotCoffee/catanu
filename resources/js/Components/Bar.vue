<script setup>

defineProps({
    bar: Array,
});


</script>
<template>
 <div class="bar" :class="'bar-' + parseInt(bar.pos_id +  1)">
 </div>
</template>