<template>
    <div :class='"alert alert-"+props.type' role="alert">
        <slot/>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props =defineProps({
type:String,
description:String
})
</script>