<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Cell from '@/Components/Cell.vue';
import Dot from '@/Components/Dot.vue';
import Bar from '@/Components/Bar.vue';
import axios from 'axios';


const props = defineProps({
    game_id: Number,
    boards: Array,
    bars: Array,
    dots: Array,
    players: Array,
    latest_turn: Array,
    my_id: Number,
    my_order: Number,
    bar_config: Array
});


// const { props } = usePage();
let latest_turn = ref(props.latest_turn);
const player_id = ref(props.my_id).value;
const my_order = ref(props.my_order).value;
let bars = ref(props.bars);
let dots = ref(props.dots);
let bar_config = ref(props.bar_config).value;

// 初回かどうかのフラグ
let first_flg = ref(false);
let dotSelectAble = ref(false);
let barSelectAble = ref(false);

// 初回かどうかのフラグ
let my_bases = ref([]);

// 建築可能な道
let can_select_road = ref([])

console.log("dots")
console.log(dots)
console.log("latest_turn")
console.log(latest_turn)
console.log("player_id")
console.log(player_id)

// 最初のターンにユーザーが選択した家
let selectedFirstBase = null;
// 最初のターンにユーザーが選択した道
let selectedFirstRoad = null;


function turnProcessing(turn){
    console.log("turn");
    console.log(turn.turn);
    if(turn.turn <= 8){
        // 最初の８ターンは拠点を選ぶ。
        // 自分のターンかどうかを判別
        if(player_id == turn.player_id){
            console.log("あなたのターンです。")
            // 自分のターンの場合
            setFirstBase();
        }
        else{
            // 自分のターンではない場合
            console.log(turn.player_name + "さんのターン")
        }

    }
    else{
        console.log("ノー")
    }
}

function setFirstBase(){
    // 家を選ぶ
    first_flg.value = true;
    dotSelectAble.value = true;
    console.log("陣地を選択してください")
    // 家に隣接している道を選ぶ
    // サーバーに送信する
}

// 家を選択
function selectBase(value){
    console.log(value + "の家が選択されました。");

    // 家の位置を仮置き
    selectedFirstBase = value;
    dots.value[selectedFirstBase].player_id = player_id;
    dots.value[selectedFirstBase].building = 0;

    // フラグを変更し、道を選択可能に、また家に隣接する道路をハイライト
    dotSelectAble.value = false;
    barSelectAble.value = true;
    my_bases.value.push(value);
    can_select_road.value = canSelectRoadByBase(my_bases);


    return ;
}
async function selectBar(value){
    console.log(value + "の道が選択されました。");

    selectedFirstRoad = value;

    // 道を仮置き
    bars.value[selectedFirstRoad].player_id = player_id;

    // フラグを変更し、選択不可能に
    first_flg.value = false;
    barSelectAble.value = false;

    // サーバーの選択した道、選択した家を送信する
    console.log("最初に選択した家")
    console.log(selectedFirstBase)
    console.log("最初に選択した道")
    console.log(selectedFirstRoad)



    await setBaseOrRoad(
        selectedFirstBase,
        selectedFirstRoad
    )

    turnEnd();
}

function canSelectRoadByBase(bases){
    let base_len = bases.value.length;
    let bar_config_len = bar_config.length;
    let can_select_road = [];

    for(let i = 0; i < base_len; i ++){
        for(let j = 0;j < bar_config_len; j ++){
            if(
                bases.value[i] == bar_config[j][0] ||
                bases.value[i] == bar_config[j][1]
            ){
                can_select_road.push(j);
            }
        }
    }
    return can_select_road;
}

async function setBaseOrRoad(base, road){

    const url = 'http://catan.hiraku-dev.site/api/setData';
    const params = {
        game_id: props.game_id,
        player_id: player_id,
        road: road,
        base: base 
    };

    await  axios.post(url, params)
    .then(response => {

        dots.value = response.data.dots;
        bars.value = response.data.bars;

        return
    })
    .catch(error => {
        console.error('Error:', error);

        return
    });
}

function turnEnd(){

    const url = 'http://catan.hiraku-dev.site/api/turnEnd';
    const params = {
        game_id: props.game_id,
        player_id: player_id,
    };

    axios.post(url, params)
    .then(response => {
        console.log(response.data.turn);
        console.log(response.data.turn.player_name + "さんのターン")
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testPusher(){
    const url = 'http://catan.hiraku-dev.site/api/test';

    axios.post(url, {})
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


onMounted(() => {
    turnProcessing(latest_turn.value);


    window.Echo.channel('catan')
        .listen('setData', (e) => {
            // プレイヤーが参加したら付け加える
            console.info("get event setData")
            console.log(e.data);
            dots.value = e.data.dots;
            bars.value = e.data.bars;
        });

    window.Echo.channel('catan')
        .listen('turnEnd', (e) => {
            // プレイヤーが参加したら付け加える
            console.info("get event turnEnd")
            console.log(e.data);
            latest_turn.value = e.data.turn;
            turnProcessing(latest_turn.value);
            console.log(e.data.turn.player_name + "さんの番です")
        });

});
</script>

<template>
    <AppLayout title="Dashboard">
        <div id="field-wrapper">
            <div id="field">
                <Cell :cell="data" :key="data.pos_id" v-for="data in boards"></Cell>
                <Dot
                    :dot="data" 
                    :key="data.pos_id" 
                    :firstFlg="first_flg" 
                    :selectAble="dotSelectAble"
                    v-for="data in dots"
                    @selectBase="selectBase"
                ></Dot>
                <Bar 
                    :bar="data" 
                    :firstFlg="first_flg" 
                    :selectAble="barSelectAble"
                    :key="data.pos_id" 
                    v-for="data in bars"
                    :canSelectRoad="can_select_road"
                    @selectBar="selectBar"
                ></Bar>
            </div>
        </div>
        <button @click="testPusher">testPusher</button>
    </AppLayout>
</template>
