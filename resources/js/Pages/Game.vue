<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Cell from '@/Components/Cell.vue';
import Dot from '@/Components/Dot.vue';
import Bar from '@/Components/Bar.vue';

defineProps({
    boards: Array,
    bars: Array,
    dots: Array,
});


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Game
            </h2>
        </template>


        <div id="field">
            <Cell :cell="data" :key="data.pos_id" v-for="data in boards"></Cell>
            <Dot :dot="data" :key="data.pos_id" v-for="data in dots"></Dot>
            <Bar :bar="data" :key="data.pos_id" v-for="data in bars"></Bar>
		</div>

    </AppLayout>
</template>
