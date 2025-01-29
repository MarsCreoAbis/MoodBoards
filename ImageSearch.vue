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
function addToCollage(imageUrl) {
  emit('image-added', imageUrl)
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
  <div>
    <h2>Image Search</h2>

    <!-- Search Input -->
    <div>
      <input v-model="localQuery" @input="searchPhotos" placeholder="Enter a keyword" />
      <button @click="searchPhotos">Search</button>
    </div>

    <!-- Photos Display -->
    <div v-for="photo in photosFromAPI" :key="photo.id" class="photo-card">
      <img :src="photo.urls.small" :alt="photo.alt_description" />
      <button @click="addToCollage(photo.urls.small)">Add to Collage</button>
      <button @click="fetchAssociatedWord" v-if="suggestionCount < maxSuggestions">
        Suggest Related
      </button>
    </div>
  </div>
</template>

<style scoped>
/* Styling for images and collage */
.photo-card {
  width: 150px;
  height: 150px;
  margin: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.photo-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

button {
  margin-top: 10px;
  padding: 5px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #369972;
}
</style>
