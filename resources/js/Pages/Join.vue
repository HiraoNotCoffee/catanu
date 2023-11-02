<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

let form = router.form({
   code: "",
})

const send = () => {
   form.post('/game/matching')
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ゲーム参加コード入力
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="send">
                        <label>ゲームコード</label>
                        <input type="text" name="name" v-model="form.code" />
                        <button type="submit">
                            テストする
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
