<script setup>
import { ref } from 'vue'

// Define Props from parent
const props = defineProps({
  savedImages: Array, // All saved images from ImageSearch
  selectedImagesForCollage: Array, // Images selected for the collage
})

// Define Emits
const emit = defineEmits(['image-selected-for-collage'])

// Toggle image selection for collage
function toggleImageSelection(imageUrl) {
  emit('image-selected-for-collage', imageUrl)
}

// Generate the collage from selected images
function generateCollage() {
  const canvas = document.getElementById('collageCanvas')
  const ctx = canvas.getContext('2d')

  const canvasWidth = 800
  const canvasHeight = 600
  const imageWidth = 200
  const imageHeight = 200

  canvas.width = canvasWidth
  canvas.height = canvasHeight

  props.selectedImagesForCollage.forEach((imageUrl, index) => {
    const img = new Image()
    img.src = imageUrl

    img.onload = () => {
      const xPos = (index % 4) * imageWidth
      const yPos = Math.floor(index / 4) * imageHeight
      ctx.drawImage(img, xPos, yPos, imageWidth, imageHeight)
    }
  })
}
</script>

<template>
  <div>
    <h3>Your Collage</h3>

    <!-- Saved Images Display -->
    <div class="saved-images-preview">
      <h4>Saved Images</h4>
      <div class="image-gallery">
        <div
          v-for="(image, index) in savedImages"
          :key="index"
          class="image-item"
          :class="{ selected: selectedImagesForCollage.includes(image) }"
          @click="toggleImageSelection(image)"
        >
          <img :src="image" :alt="'Saved image ' + (index + 1)" />
        </div>
      </div>
    </div>

    <!-- Generate Collage Button -->
    <button @click="generateCollage">Generate Collage</button>

    <!-- Collage Canvas -->
    <canvas id="collageCanvas"></canvas>
  </div>
</template>

<style scoped>
.saved-images-preview {
  margin-top: 20px;
}

.saved-images-preview .image-gallery {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.saved-images-preview img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
  cursor: pointer;
}

.image-item.selected {
  border: 3px solid #42b983;
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
