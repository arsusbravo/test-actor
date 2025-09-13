<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-2xl mx-auto">
      <h1 class="text-3xl font-bold mb-6">Actor Submission</h1>
      
      <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow">
        <div class="mb-4">
          <label class="block mb-2 font-semibold text-gray-800 text-lg">Email:</label>
          <input 
            v-model="form.email" 
            type="email" 
            required 
            class="w-full p-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
            placeholder="Enter your email address"
          />
          <div v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</div>
        </div>

        <div class="mb-6">
          <label class="block mb-2 font-semibold text-gray-800 text-lg">Description:</label>
          <textarea 
            v-model="form.description" 
            rows="4" 
            required 
            class="w-full p-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
            placeholder="Please enter your first name and last name, and also provide your address."
          ></textarea>
          <div v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</div>
        </div>

        <div class="flex gap-3">
          <button 
            type="submit" 
            :disabled="loading"
            class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50 text-lg"
          >
            {{ loading ? 'Processing...' : 'Submit' }}
          </button>
          <button 
            type="button" 
            @click="$inertia.visit('/actors')"
            class="bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-gray-700 text-lg"
          >
            View All
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  email: '',
  description: ''
})

const errors = ref({})
const loading = ref(false)

const submit = () => {
  loading.value = true
  errors.value = {}
  
  router.post('/actors', form.value, {
    onError: (pageErrors) => {
      errors.value = pageErrors
    },
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
