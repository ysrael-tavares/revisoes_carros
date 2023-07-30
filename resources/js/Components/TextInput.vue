<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
            required: true,
        },
        typeFormat: {
            type: String,
            default: 'text'
        }
    },
    emits: ['update:modelValue'],

}
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
