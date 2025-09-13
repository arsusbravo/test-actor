<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Actor Submissions</h1>
        <button 
          @click="$inertia.visit('/actors/create')"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600"
        >
          Submit New Actor
        </button>
      </div>

      <div v-if="actors.length === 0" class="bg-white p-8 rounded-lg shadow text-center">
        <p class="text-gray-500 mb-4 text-lg">No submissions yet.</p>
        <button 
          @click="$inertia.visit('/actors/create')"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600"
        >
          Submit First Actor
        </button>
      </div>

      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-4 text-left font-medium text-gray-900">First Name</th>
              <th class="px-6 py-4 text-left font-medium text-gray-900">Address</th>
              <th class="px-6 py-4 text-left font-medium text-gray-900">Gender</th>
              <th class="px-6 py-4 text-left font-medium text-gray-900">Height</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="actor in actors" :key="actor.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 text-gray-900 font-medium">{{ actor.first_name || 'N/A' }}</td>
              <td class="px-6 py-4 text-gray-900">{{ actor.address || 'N/A' }}</td>
              <td class="px-6 py-4 text-gray-900">{{ actor.gender || 'N/A' }}</td>
              <td class="px-6 py-4 text-gray-900">{{ actor.height || 'N/A' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  actors: Array
})
</script>
