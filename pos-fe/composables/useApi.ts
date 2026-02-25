export function useApi() {
  const config = useRuntimeConfig()
  const { token, clearToken } = useAuth()

  const baseURL = config.public.apiBase as string

  async function request<T>(
    path: string,
    options: RequestInit & { body?: unknown } = {}
  ): Promise<T> {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...(typeof options.headers === 'object' && !(options.headers instanceof Headers)
        ? (options.headers as Record<string, string>)
        : {}),
    }
    if (token.value) {
      headers.Authorization = `Bearer ${token.value}`
    }
    const res = await $fetch<T>(path, {
      baseURL: baseURL.replace(/\/$/, '') + '/api',
      ...options,
      headers: {
        ...headers,
        ...(options.headers as Record<string, string>),
      },
      body: options.body,
    }).catch((err) => {
      if (err?.response?.status === 401) {
        clearToken()
      }
      throw err
    })
    return res
  }

  return {
    baseURL,
    request,
    get: <T>(path: string, opts?: RequestInit) => request<T>(path, { ...opts, method: 'GET' }),
    post: <T>(path: string, body?: unknown, opts?: RequestInit) =>
      request<T>(path, { ...opts, method: 'POST', body }),
    put: <T>(path: string, body?: unknown, opts?: RequestInit) =>
      request<T>(path, { ...opts, method: 'PUT', body }),
    delete: <T>(path: string, opts?: RequestInit) => request<T>(path, { ...opts, method: 'DELETE' }),
  }
}
