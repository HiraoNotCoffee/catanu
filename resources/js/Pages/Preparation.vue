<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3'

const props = defineProps({
    game_id: Number,
    players: Array,
    code: String,
});

let players = ref(props.players);
let game_id = ref(props.game_id);



let form = router.form({
   game_id: props.game_id,
})

onMounted(() => {
    // 人数が4人だったらゲーム画面に遷移
    if (players.value.length === 4) {
       startGame();
    }

    window.Echo.channel('catan')
        .listen('MatchingRoom', (e) => {
            // プレイヤーが参加したら付け加える
            players.value = e.data;
        });

    window.Echo.channel('catan')
    .listen('startGame', (e) => {
        // プレイヤーが参加したら付け加える
        startGame();
    });
});

console.log("ゲームコード")
console.log(props.code)



watch(players, (newPlayers, oldPlayers) => {
    if (newPlayers.length === 4) {
        startGame();
    }
});

function startGame(){
    form.post('/game/board')
}

</script>


<template>
    <AppLayout title="Dashboard">
        <p v-for="item in players">
            {{ item }}
        </p>

        ゲームコード : {{ code }}
        <button @click="startGame">開始する</button>
    </AppLayout>
</template>
