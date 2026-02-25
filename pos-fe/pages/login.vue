<template>
  <div class="page">
    <header class="header">
      <h1 class="title">Log in</h1>
      <p class="subtitle">Sign in to your account</p>
    </header>
    <form class="form card" @submit.prevent="submit">
      <p v-if="error" class="error">{{ error }}</p>
      <div class="field">
        <label for="email">Email</label>
        <input
          id="email"
          v-model="email"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input
          id="password"
          v-model="password"
          type="password"
          autocomplete="current-password"
          required
          placeholder="••••••••"
        />
      </div>
      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Signing in…' : 'Sign in' }}
      </button>
      <p class="footer">
        Don't have an account? <NuxtLink to="/register">Register</NuxtLink>
      </p>
    </form>
  </div>
</template>

<script setup lang="ts">
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const { setToken, setUser } = useAuth()
const router = useRouter()
const { post } = useApi()

async function submit() {
  error.value = ''
  loading.value = true
  try {
    const res = await post<{ user: { id: number; name: string; email: string }; token: string }>(
      '/login',
      { email: email.value, password: password.value }
    )
    setToken(res.token)
    setUser(res.user)
    await router.push('/')
  } catch (e: unknown) {
    const err = e as { data?: { message?: string; errors?: Record<string, string[]> }; message?: string }
    const data = err?.data
    if (data?.errors?.email?.length) {
      error.value = data.errors.email[0]
    } else {
      error.value = data?.message || err?.message || 'Login failed'
    }
  } finally {
    loading.value = false
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
.form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.field {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}
.field label {
  font-weight: 500;
  font-size: 0.9rem;
}
.field input {
  padding: 0.75rem 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1rem;
  min-height: 48px;
}
.error {
  color: #dc2626;
  font-size: 0.9rem;
  margin: 0;
}
.btn {
  padding: 0.875rem 1.25rem;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  margin-top: 0.25rem;
}
.btn-primary {
  background: #2563eb;
  color: #fff;
}
.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.footer {
  text-align: center;
  margin: 0;
  font-size: 0.9rem;
  color: #666;
}
.footer a {
  color: #2563eb;
  text-decoration: none;
}
</style>
