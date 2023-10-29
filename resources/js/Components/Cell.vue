<script setup>

const props = defineProps({
    cell: Array,
});

let style = {
    "background-image" : "url(" + props.cell.cell.img_path + ")"
}

</script>

<template>
 <div class="cell" :class="'cell-' + parseInt(cell.pos_id +  1)"
    :style="style">
    <p :class="'dice-' + cell.dice_id" >{{ cell.dice_id }}</p>
 </div>
</template>