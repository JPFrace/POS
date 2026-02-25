export default defineNuxtRouteMiddleware((to) => {
  const { isLoggedIn } = useAuth()
  if (!isLoggedIn.value && to.path.startsWith('/dashboard')) {
    return navigateTo('/login')
  }
})
