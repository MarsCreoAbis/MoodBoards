<script setup>
import { ref, watch, onMounted } from 'vue'

const ACCESS_KEY = '7yDJExOqcReYxSGtUKYRTQz_gsv3niZHUvMe9ZTIWww'

// Define Props from parent
const props = defineProps({
  query: String, // Initial query from parent
})

// Define Emits
const emit = defineEmits(['update:query', 'image-added'])

// Local state
const localQuery = ref(props.query) // Track query changes
const suggestionCount = ref(0)
const maxSuggestions = 9
const photosFromAPI = ref([]) // Store fetched images

// Fetch photos from Unsplash API
async function searchPhotos() {
  if (!localQuery.value) return

  const url = `https://api.unsplash.com/search/photos?query=${localQuery.value}&per_page=6`
  const headers = { Authorization: `Client-ID ${ACCESS_KEY}` }

  try {
    const response = await fetch(url, { headers })
    const data = await response.json()
    photosFromAPI.value = data.results || []
  } catch (error) {
    console.error('Error fetching photos:', error)
  }
}

// Add photo to collage and emit to parent
function addToCollage(photo) {
  emit('image-added', photo.urls.full) // Use the full-size image URL
}

// Fetch related keywords based on the current search query
async function fetchAssociatedWord() {
  if (suggestionCount.value >= maxSuggestions) {
    alert("You've reached the maximum number of suggestions for this session!")
    return
  }

  try {
    const response = await fetch(`https://api.datamuse.com/words?rel_gen=${localQuery.value}`)
    const data = await response.json()

    if (data.length > 0) {
      const uniqueWords = data
        .map((wordObj) => wordObj.word)
        .filter((word, index, self) => self.indexOf(word) === index) // Remove duplicates

      if (uniqueWords.length > 0) {
        localQuery.value = uniqueWords[Math.floor(Math.random() * uniqueWords.length)]
        suggestionCount.value++

        // Emit new query to parent and fetch new images immediately
        emit('update:query', localQuery.value)
        searchPhotos()
      }
    }
  } catch (error) {
    console.error('Error fetching suggestions:', error)
  }
}

// Fetch photos when the component mounts
onMounted(() => {
  searchPhotos()
})

// Watch for query changes and fetch new images
watch(localQuery, (newQuery) => {
  searchPhotos()
})

// Watch for changes to the query prop from the parent
watch(
  () => props.query,
  (newQuery) => {
    localQuery.value = newQuery
  },
)
</script>

<template>
  <div class="image-search">
    <h2>Choose the mood of your board...</h2>

    <!-- Search Input -->
    <div class="search-container">
      <input v-model="localQuery" @input="searchPhotos" placeholder="Enter a keyword" />
      <button @click="searchPhotos">Search</button>
    </div>

    <!-- Photos Display -->
    <div class="photo-grid">
      <div v-for="photo in photosFromAPI" :key="photo.id" class="photo-card">
        <img :src="photo.urls.small" :alt="photo.alt_description" />
        <div class="button-group">
          <button @click="addToCollage(photo)">Add to Board</button>
          <button @click="fetchAssociatedWord" v-if="suggestionCount < maxSuggestions">
            Suggest Related
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* General Styling */
.image-search {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
  overflow: hidden; /* Prevent horizontal overflow */
}

h2 {
  margin-bottom: 20px;
  font-style: oblique;
  font-size: 1.5rem;
  color: #5c8038;
}

/* Search Input Styling */
.search-container {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  flex: 1;
  max-width: 100%; /* Ensure input doesn't overflow */
}

button[type='button'] {
  padding: 10px 20px;
  background-color: #2c5530;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button[type='button']:hover {
  background-color: #5c8038;
}

/* Photos Display */
.photo-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px; /* Increased spacing between cards */
  justify-content: center; /* Center cards in the grid */
}

.photo-card {
  width: calc(33.333% - 14px); /* 3 cards per row with spacing */
  max-width: 100%; /* Ensure cards don't overflow */
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  background-color: #f9f9f9;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.photo-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px 10px 0 0;
}

.button-group {
  display: flex;
  flex-direction: column;
  gap: 10px; /* Increased spacing between buttons */
  padding: 15px;
  width: 100%;
}

button {
  padding: 10px;
  background-color: #2c5530;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  text-align: center;
  font-size: 0.9rem;
}

button:hover {
  background-color: #5c8038;
}

/* Responsive Design */
@media (max-width: 768px) {
  .photo-card {
    width: calc(50% - 10px); /* 2 cards per row on smaller screens */
  }
}

@media (max-width: 480px) {
  .photo-card {
    width: 100%; /* 1 card per row on mobile */
  }
}
</style>
