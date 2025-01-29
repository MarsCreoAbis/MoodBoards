<script setup>
import { ref, watch } from 'vue';
import Main2 from './Main2.vue';

const showMain2 = ref(false); // Controls visibility of Main2.vue
const query = ref(localStorage.getItem('query') || ''); // Load query from localStorage
const photos = ref(JSON.parse(localStorage.getItem('photos') || '[]')); // Load photos from localStorage

function toggleMain2() {
  showMain2.value = !showMain2.value;
}

// Watch for changes to showMain2 and persist query/photos state when hidden
watch(showMain2, (newValue) => {
  if (!newValue) {
    localStorage.setItem('query', query.value);
    localStorage.setItem('photos', JSON.stringify(photos.value));
  }
});
</script>

<template>
  <div id="app">
    <!-- Main Page Content -->
    <header class="main-header">
      <h1>Moodboard Maker</h1>
      <button @click="toggleMain2">START</button>
    </header>

    <!-- Embedded Subpage (Appears Below When Toggled) -->
    <section v-if="showMain2" class="main2-container">
      <Main2 :query="query" :photos="photos" />
    </section>
  </div>
</template>

<style scoped>
/* Main Page Formatting */
.main-header {
  text-align: center;
  padding: 2rem;
  background-color: #42b983;
  color: white;
}

main {
  padding: 2rem;
  text-align: center;
}

/* Main2.vue Container */
.main2-container {
  margin-top: 2rem;
  padding: 1rem;
  border-top: 2px solid #ddd;
  text-align: center;
}
</style>
