<template>
  <div class="page">
    <header class="header">
      <NuxtLink to="/" class="back">← Home</NuxtLink>
      <h1 class="title">Dashboard</h1>
      <p class="subtitle">Welcome, {{ user?.name }}</p>
    </header>
    <main class="main card">
      <p v-if="!user">Loading…</p>
      <div v-else>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p>Protected content — you are logged in.</p>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const { user } = useAuth()
</script>

<style scoped>
.page {
  padding: 1.5rem;
  max-width: 28rem;
  margin: 0 auto;
}
.back {
  display: inline-block;
  margin-bottom: 1rem;
  color: #2563eb;
  text-decoration: none;
  font-size: 0.95rem;
}
.header {
  margin-bottom: 1.5rem;
}
.title {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 0.25rem 0;
}
.subtitle {
  color: #666;
  margin: 0;
}
.main.card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
</style>
