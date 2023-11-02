<script setup>
import { ref, computed, defineEmits } from 'vue';

const props = defineProps({
    dot: Object,
    firstFlg: Boolean,
    selectAble: Boolean,
});

const dot_class = computed(() => ({
    'selectable': props.firstFlg && props.selectAble && props.dot.player_id == null,
    ['dot-player-' + parseInt(props.dot.player_id % 4)] : props.dot.player_id != null,
    ['dot-type-' + props.dot.building] : props.dot.building != null,
    ['dot-' + parseInt(props.dot.pos_id + 1)]: true
}));

const emit = defineEmits(["selectBase"]);

function selectBase(){
    if(props.firstFlg && props.selectAble && props.dot.player_id == null){
        emit("selectBase", props.dot.pos_id);
    }
}
</script>
<template>
<p>{{ dot }} firstFlg :{{ firstFlg }} selectAble :{{ selectAble}}</p>

<div class="dot" :class="dot_class" @click="selectBase">
</div>
</template>