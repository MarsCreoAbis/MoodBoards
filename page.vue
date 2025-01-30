<script setup>
import { ref, watch } from 'vue'
import ImageSearch from './components/ImageSearch.vue'
import CollageManager from './components/CollageManager.vue'

const showMain2 = ref(false) // Controls visibility of Main2.vue
const query = ref(localStorage.getItem('query') || '') // Load query from localStorage
const photos = ref(JSON.parse(localStorage.getItem('photos') || '[]')) // Load photos from localStorage

// Shared state for saved images and selected images for collage
const savedImages = ref([]) // All images saved from ImageSearch
const selectedImagesForCollage = ref([]) // Images selected for the collage

// Toggle visibility of the subpage
function toggleMain2() {
  showMain2.value = !showMain2.value
}

// Watch for changes to showMain2 and persist query/photos state when hidden
watch(showMain2, (newValue) => {
  if (!newValue) {
    localStorage.setItem('query', query.value)
    localStorage.setItem('photos', JSON.stringify(photos.value))
  }
})

// Handle image selection from ImageSearch.vue
function handleImageAdded(imageUrl) {
  if (!savedImages.value.includes(imageUrl)) {
    savedImages.value.push(imageUrl)
  }
}

// Handle image selection for collage in CollageManager.vue
function handleImageSelectedForCollage(imageUrl) {
  if (!selectedImagesForCollage.value.includes(imageUrl)) {
    selectedImagesForCollage.value.push(imageUrl)
  } else {
    // Remove the image if it's already selected
    selectedImagesForCollage.value = selectedImagesForCollage.value.filter(
      (img) => img !== imageUrl,
    )
  }
}
</script>

<script>
export default {
  methods: {
    goToSubPage() {
      this.$router.push('/SubPage.vue')
    },
  },
}
</script>

<template>
  <div id="app">
    <!-- Main Page Content -->
    <header class="main-header">
      <h1>Moodboarder 3000</h1>
      <button @click="toggleMain2">START</button>
      <main>
        <h2>Lawful Good Moodboarding Machine</h2>
        <button @click="goToSubPage">Your Account</button>
      </main>
    </header>

    <!-- Embedded Subpage (Appears Below When Toggled) -->
    <section v-if="showMain2" class="main2-container">
      <!-- Image Search Component -->
      <ImageSearch
        :query="query"
        @update:query="(newQuery) => (query = newQuery)"
        @image-added="handleImageAdded"
      />

      <!-- Collage Manager Component -->
      <CollageManager
        :savedImages="savedImages"
        :selectedImagesForCollage="selectedImagesForCollage"
        @image-selected-for-collage="handleImageSelectedForCollage"
      />
    </section>
  </div>
</template>

<style scoped>
/* Main Page Formatting */
.main-header {
  text-align: center;
  padding: 2rem;
  background-color: #2c5530;
  color: white;
}

button {
  margin: 7px;
  margin-right: 3px;
  padding: 7px;
  background-color: #5c8038;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
}

main {
  padding: 2rem;
  text-align: center;
}

h1 {
  color: white;
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
}

/* Main2.vue Container */
.main2-container {
  margin-top: 2rem;
  padding: 1rem;
  border-top: 2px solid #ddd;
  text-align: center;
}
</style>
