<template>
    <div>
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-spccolor-600">
                    {{ title }}
                </h1>
                <slot name="header"></slot>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <!-- Your content -->
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    <slot></slot>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
});
</script>

<style></style>