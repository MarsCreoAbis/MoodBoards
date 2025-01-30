<script setup>
import { ref, onMounted } from 'vue'
import html2canvas from 'html2canvas' // Import html2canvas

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

// Dynamic collage layout
const collageGrid = ref(null)

onMounted(() => {
  if (collageGrid.value) {
    arrangeCollage()
  }
})

function arrangeCollage() {
  const grid = collageGrid.value
  if (!grid) return

  // Reset the grid
  grid.style.gridAutoRows = 'auto'
  grid.style.gap = '10px'

  // Arrange images dynamically
  const images = grid.querySelectorAll('.collage-item img')
  images.forEach((img) => {
    img.onload = () => {
      const aspectRatio = img.naturalWidth / img.naturalHeight

      // Classify images as vertical, horizontal, or square-like
      if (aspectRatio > 1.2) {
        img.parentElement.classList.add('horizontal')
      } else if (aspectRatio < 0.8) {
        img.parentElement.classList.add('vertical')
      } else {
        img.parentElement.classList.add('square')
      }
    }
  })
}

// Download Collage as PNG
function downloadCollage() {
  if (!collageGrid.value) return

  // Use html2canvas to capture the collage area
  html2canvas(collageGrid.value, {
    useCORS: true, // Allow cross-origin images
    scale: 2, // Maintain original size
    logging: true, // Optional: Enable logging for debugging
    allowTaint: true, // Allow tainted images (if CORS is not enabled)
    backgroundColor: null, // Ensure transparent background
  }).then((canvas) => {
    // Convert canvas to PNG image
    const image = canvas.toDataURL('image/png')

    // Create a temporary link to trigger the download
    const link = document.createElement('a')
    link.href = image
    link.download = 'collage.png' // Set the file name
    link.click() // Trigger the download
  })
}
</script>

<template>
  <div>
    <h3>Your MOODBOARD</h3>

    <!-- Saved Images Display (Squares) -->
    <div class="saved-images-preview">
      <h4>The Chosen</h4>
      <div class="collage-grid">
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
    <button @click="arrangeCollage">Generate</button>

    <!-- Download Collage Button -->
    <button @click="downloadCollage" v-if="selectedImagesForCollage.length > 0">
      Download Collage
    </button>

    <!-- Collage Display (Dynamic Grid) -->
    <div v-if="selectedImagesForCollage.length > 0" ref="collageGrid" class="collage-grid">
      <div v-for="(image, index) in selectedImagesForCollage" :key="index" class="collage-item">
        <img :src="image" :alt="'Collage image ' + (index + 1)" />
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Saved Images Display (Squares) */
.saved-images-preview {
  margin-top: 20px;
}

.saved-images-preview .image-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Fluid grid */
  gap: 10px; /* Constant margin between images */
}

.saved-images-preview .image-item {
  width: 150px;
  height: 150px;
  overflow: hidden; /* Ensure images are cropped to squares */
  border-radius: 10px;
  cursor: pointer;
}

.saved-images-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Crop images to fit squares */
  border-radius: 10px;
}

.image-item.selected {
  border: 3px solid #2c5530;
}

/* Generate Collage Button */
button {
  margin: 20px;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  background-color: #2c5530;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

/* Collage Grid (Dynamic Layout) */
.collage-grid {
  margin-top: 20px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Fluid grid */
  grid-auto-rows: 200px; /* Fixed row height */
  gap: 10px; /* Constant margin between images */
  max-width: 1200px; /* Limit the width of the collage */
  margin: 0 auto; /* Center the collage */
}

.collage-item {
  position: relative;
  overflow: hidden; /* Ensure images are cropped to fit */
  border-radius: 10px;
}

.collage-item img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Crop images to fit the grid cells */
  border-radius: 10px;
}

/* Dynamic Sizing Based on Aspect Ratio */
.collage-item.horizontal {
  grid-column: span 2; /* Horizontal images span 2 columns */
}

.collage-item.vertical {
  grid-row: span 2; /* Vertical images span 2 rows */
}
</style>
