<template>
  <section id="user-auth">
    <h2>User Authentication</h2>
    <form @submit.prevent="submitForm">
      <label for="email">Email:</label>
      <input id="email" v-model="email" type="email" required />

      <label for="password">Password:</label>
      <input id="password" v-model="password" type="password" required />

      <button type="submit">{{ isLogin ? 'Login' : 'Sign Up' }}</button>
    </form>
    <button @click="toggleMode">
      Switch to {{ isLogin ? 'Sign Up' : 'Login' }}
    </button>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const isLogin = ref(true);
const email = ref('');
const password = ref('');

function toggleMode() {
  isLogin.value = !isLogin.value;
}

function submitForm() {
  console.log(isLogin.value ? 'Logging in...' : 'Signing up...');
}
</script>

<style scoped>
#user-auth {
  text-align: center;
  margin-top: 2rem;
}
</style>
