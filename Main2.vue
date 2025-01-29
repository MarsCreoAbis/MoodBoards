<script setup>
import { ref } from 'vue';

const ACCESS_KEY = '7yDJExOqcReYxSGtUKYRTQz_gsv3niZHUvMe9ZTIWww';
const query = ref(''); // Query for searching photos
const photos = ref([]); // Array of photo results
const suggestionCount = ref(0); // Tracks the number of suggestions
const maxSuggestions = 9; // Max number of suggestions allowed

// Fetch photos based on the current query
async function searchPhotos() {
  if (!query.value) return;

  const url = `https://api.unsplash.com/search/photos?query=${query.value}&per_page=6`;
  const headers = { Authorization: `Client-ID ${ACCESS_KEY}` };

  try {
    const response = await fetch(url, { headers });
    const data = await response.json();
    photos.value = data.results || [];
    console.log('Photos fetched:', photos.value); // Debug log
  } catch (error) {
    console.error('Error fetching photos:', error);
  }
}

// Fetch associated words (related suggestions) based on the query
async function fetchAssociatedWord() {
  if (suggestionCount.value >= maxSuggestions) {
    alert("You've reached the maximum number of suggestions for this session!");
    return;
  }

  console.log('Current suggestion count:', suggestionCount.value); // Debug log
  
  try {
    const response = await fetch(`https://api.datamuse.com/words?rel_gen=${query.value}`);
    const data = await response.json();

    if (data.length > 0) {
      const uniqueWords = data
        .map((wordObj) => wordObj.word)
        .filter((word, index, self) => self.indexOf(word) === index); // Remove duplicates

      if (uniqueWords.length > 0) {
        query.value = uniqueWords[Math.floor(Math.random() * uniqueWords.length)];
        suggestionCount.value++; // Increment the counter after each suggestion
        console.log('New query after suggestion:', query.value); // Debug log
        console.log('Updated suggestion count:', suggestionCount.value); // Debug log
        searchPhotos(); // Trigger photo search
      }
    }
  } catch (error) {
    console.error('Error fetching suggestions:', error);
  }
}
</script>

<template>
  <section id="image-search">
    <h2>Image Search</h2>
    <form @submit.prevent="searchPhotos">
      <input v-model="query" placeholder="Enter a keyword" />
      <button type="submit">Search</button>
    </form>

    <div id="result">
      <div v-for="photo in photos" :key="photo.id" class="photo-card">
        <img :src="photo.urls.small" :alt="photo.alt_description" />
        <button @click="fetchAssociatedWord" v-if="suggestionCount < maxSuggestions">Suggest Related</button>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Styling for the search section */
#image-search {
  text-align: center;
}

/* Styling for the result section */
#result {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

/* Styling for each photo card */
.photo-card {
  width: 150px;
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  border-radius: 10px;
  background: #f0f0f0;
}

/* Make images fit in squares */
.photo-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

/* Styling for buttons */
button {
  margin-top: 5px;
  padding: 5px 10px;
  font-size: 14px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  position: absolute;
  bottom: 10px;
}

button:hover {
  background-color: #369972;
}
</style>
