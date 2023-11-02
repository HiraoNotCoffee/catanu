<script setup>
import { ref, computed, defineEmits } from 'vue';

const props = defineProps({
    bar: Array,
    firstFlg: Boolean,
    selectAble: Boolean,
    canSelectRoad: Array,
});
// let can_select = props.canSelectRoad.includes(props.bar.pos_id);


const can_select = computed(() => props.canSelectRoad.includes(props.bar.pos_id));

const bar_class = computed(() => ({
    'selectable': props.firstFlg && props.selectAble && can_select.value && props.bar.player_id == null,
    ['bar-player-' + parseInt(props.bar.player_id % 4)] : props.bar.player_id != null,
    ['bar-' + parseInt(props.bar.pos_id + 1)]: true,
}));

const emit = defineEmits(["selectBar"]);

function selectBar(){
    if(props.firstFlg && props.selectAble && can_select.value && props.bar.player_id == null){
        emit("selectBar", props.bar.pos_id);
    }
}


</script>
<template>
 <div class="bar"  :class="bar_class"  @click="selectBar">
 </div>
</template>