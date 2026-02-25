const TOKEN_KEY = 'pos_auth_token'

export function useAuth() {
  const token = useState<string | null>(TOKEN_KEY, () => {
    if (import.meta.client && typeof localStorage !== 'undefined') {
      return localStorage.getItem(TOKEN_KEY)
    }
    return null
  })

  const user = useState<{ id: number; name: string; email: string } | null>('pos_user', () => null)

  const isLoggedIn = computed(() => !!token.value)

  function setToken(newToken: string) {
    token.value = newToken
    if (import.meta.client && typeof localStorage !== 'undefined') {
      localStorage.setItem(TOKEN_KEY, newToken)
    }
  }

  function clearToken() {
    token.value = null
    user.value = null
    if (import.meta.client && typeof localStorage !== 'undefined') {
      localStorage.removeItem(TOKEN_KEY)
    }
  }

  function setUser(u: { id: number; name: string; email: string } | null) {
    user.value = u
  }

  return {
    token,
    user,
    isLoggedIn,
    setToken,
    setUser,
    clearToken,
  }
}
