<template>
  <section id="image-gen">
    <h2>Generate an Image</h2>
    <button @click="generateImage">Generate</button>
    <div v-if="imageUrl">
      <img :src="imageUrl" alt="Generated" />
      <button @click="saveImage">Save to Profile</button>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const imageUrl = ref(null);

function generateImage() {
  // Mock generation logic
  imageUrl.value = 'https://via.placeholder.com/300';
}

function saveImage() {
  // Call backend to save image
  console.log('Saving image:', imageUrl.value);
}
</script>

<style scoped>
#image-gen {
  text-align: center;
}
</style>
