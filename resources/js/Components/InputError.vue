<script setup>
defineProps({
    message: {
        type: [String, Array],
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600" v-if="typeof message === 'string'">
            {{message}}
        </p>
        <p class="text-sm text-red-600" v-for="text in message" v-else>
            {{ text }}
        </p>
    </div>
</template>
