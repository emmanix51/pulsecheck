<template>
    <div class="page-container">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
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

<style scoped>
.page-container {
    position: relative; /* To position the pseudo-element inside */
}

.page-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('/spcbg.png');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    opacity: 0.3; /* 30% opacity */
    z-index: 0; /* Behind the content */
}

main {
    position: relative; /* Ensures content is on top of the background */
    z-index: 1; /* Makes sure content is above the background */
}

header {
    position: relative; /* Ensures header is on top of the background */
    z-index: 2;
}

h1 {
    z-index: 3; /* Makes sure the title is always above other content */
}
</style>
