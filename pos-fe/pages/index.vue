<template>
  <div class="page home">
    <header class="header">
      <h1 class="title">POS Mobile</h1>
      <p v-if="isLoggedIn" class="subtitle">Hello, {{ user?.name }}</p>
      <p v-else class="subtitle">Point of Sale</p>
    </header>
    <main class="main">
      <div v-if="isLoggedIn" class="card actions">
        <NuxtLink to="/dashboard" class="btn btn-primary">Dashboard</NuxtLink>
        <button type="button" class="btn btn-outline" @click="logout">Log out</button>
      </div>
      <div v-else class="card actions">
        <NuxtLink to="/login" class="btn btn-primary">Log in</NuxtLink>
        <NuxtLink to="/register" class="btn btn-outline">Register</NuxtLink>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
const { isLoggedIn, user, clearToken } = useAuth()
const router = useRouter()
const { post } = useApi()

async function logout() {
  try {
    await post('/logout')
  } finally {
    clearToken()
    await router.push('/')
  }
}
</script>

<style scoped>
.page {
  padding: 1.5rem;
  max-width: 28rem;
  margin: 0 auto;
}
.header {
  text-align: center;
  margin-bottom: 2rem;
}
.title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0 0 0.25rem 0;
}
.subtitle {
  color: #666;
  margin: 0;
}
.main {
  flex: 1;
}
.card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.actions {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.btn {
  padding: 0.875rem 1.25rem;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: opacity 0.15s;
}
.btn:active {
  opacity: 0.9;
}
.btn-primary {
  background: #2563eb;
  color: #fff;
}
.btn-outline {
  background: transparent;
  color: #2563eb;
  border: 2px solid #2563eb;
}
</style>
