<script setup>
import { ref } from 'vue';

const showHeader = ref(true); // Controls header visibility

function main_page() {
  showHeader.value = !showHeader.value; // Toggles between header and subpage
}

function userAccount() {
  showHeader.value = !showHeader.value; 
}

function board_save() {
  showHeader.value = !showHeader.value; 
}

</script>

<template>
  <div id="app">
    <!-- Centered Header -->
    <header v-if="showHeader" class="centered-header">
      <div class="wrapper">
        <h1>Moodboarder 3000</h1>
        <button class="toggle-button" @click="main_page">GENERATE</button>
      </div>
    </header>

    <!-- Subpage -->
    <section v-else class="subpage">
      <h2>Choose the mood of your board...</h2>
      <p>tutaj trzeba wrzucić generator</p>
      <button class="toggle-button" @click="board_save">SAVE THE MOOD</button>
      <button class="toggle-button" @click="main_page">Go back</button>
    </section>

    <!-- Fixed Footer -->
    <footer>
      <button class="account-button" @click="userAccount">My Account</button>
    </footer>
  </div>
</template>

<style scoped>
/* Centered Header */
.centered-header {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f9f9f9;
  text-align: center;
  transition: opacity 0.5s ease;
}

.centered-header .wrapper {
  text-align: center;
}

.toggle-button {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.toggle-button:hover {
  background-color: #369972;
}

/* Subpage */
.subpage {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #eef;
  text-align: center;
  transition: opacity 0.5s ease;
}

/* Fixed Footer */
footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 1rem;
  background-color: #333;
  color: white;
  text-align: center;
}

.account-button {
  background-color: #42b983;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.account-button:hover {
  background-color: #369972;
}
</style>
