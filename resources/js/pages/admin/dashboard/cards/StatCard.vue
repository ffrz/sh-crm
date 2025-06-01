<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    count: {
        type: [String, Number],
        required: true
    },
    label: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        default: 'info'
    },
    bgColor: {
        type: String,
        default: '#888'
    },
    sideColor: {
        type: String,
        default: '#666'
    },
    to: {
        type: String,
        required: true
    },
    wrapperClass: {
        type: String,
        default: 'col-md-4 col-sm-6 col-xs-12'
    }
})

function handleClick() {
    router.visit(props.to)
}
</script>


<template>
    <div :class="wrapperClass">
        <q-item :style="`background-color: ${bgColor}`" class="q-pa-none" clickable @click="handleClick">
            <q-item-section side :style="`background-color: ${sideColor}`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" :name="icon" color="white" size="24px" />
            </q-item-section>
            <q-item-section class="q-pa-md q-ml-none text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                    {{ count }}
                </q-item-label>
                <q-item-label>{{ label }}</q-item-label>
            </q-item-section>
        </q-item>
    </div>
</template>
