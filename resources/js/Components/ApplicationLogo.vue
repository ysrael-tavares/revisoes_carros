<template>
    <img :src="image"/>
</template>

<script>
import image from '@/Images/logo_revisoes.jpeg'

export default {
    data: function () {
        return {
            image: image
        }
    }
}
</script>
