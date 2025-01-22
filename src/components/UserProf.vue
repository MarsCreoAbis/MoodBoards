<template>
  <footer id="user-profile">
    <h2>Your Profile</h2>
    <div v-if="savedImages.length > 0">
      <div v-for="image in savedImages" :key="image.id" class="image-card">
        <img :src="image.url" alt="Saved" />
        <button @click="removeImage(image.id)">Remove</button>
      </div>
    </div>
    <p v-else>No saved images yet.</p>
  </footer>
</template>

<script setup>
import { ref } from 'vue';

const savedImages = ref([]);

function removeImage(imageId) {
  savedImages.value = savedImages.value.filter((image) => image.id !== imageId);
}
</script>

<style scoped>
#user-profile {
  margin-top: 4rem;
  text-align: center;
}
.image-card {
  display: inline-block;
  margin: 1rem;
}
</style>
