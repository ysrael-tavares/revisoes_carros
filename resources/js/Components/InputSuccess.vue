<script setup>
defineProps({
    message: {
        type: [String, Array],
    },
});
</script>

<template>
    <div v-show="message" class="bg-green-100 py-3 px-3 rounded">
        <p class="text-sm text-green-600">
            {{ message }}
        </p>
    </div>
</template>
