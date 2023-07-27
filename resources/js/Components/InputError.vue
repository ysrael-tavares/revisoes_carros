<script setup>
defineProps({
    message: {
        type: [String, Array],
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600" v-for="text in message">
            {{ text }}
        </p>
    </div>
</template>
