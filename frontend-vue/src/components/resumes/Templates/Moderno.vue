<script setup>
defineProps({
    resume: Object // Recebe o objeto completo do currículo
});
</script>

<template>
    <div class="bg-white p-8 max-w-[210mm] mx-auto min-h-[297mm] shadow-lg">
        <header class="border-b-4 border-blue-600 pb-4 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 uppercase">{{ resume.name }}</h1>
            <p class="text-blue-600 font-medium">{{ resume.title }}</p> <div class="text-sm text-gray-500 mt-2 flex gap-4">
                <span>{{ resume.email }}</span>
                <span>{{ resume.phone }}</span>
            </div>
        </header>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-3">Experiência</h2>
            <div v-for="job in resume.experiences" :key="job.id" class="mb-4">
                <h3 class="font-bold">{{ job.company }}</h3>
                <p class="text-sm text-gray-600">{{ job.role }} | {{ job.date_start }} - {{ job.date_end }}</p>
                <p class="mt-1 text-gray-700">{{ job.description }}</p>
            </div>
        </section>
    </div>
</template>