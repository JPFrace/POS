<template>
  <div class="layout">
    <slot />
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
.layout {
  min-height: 100vh;
  min-height: 100dvh;
  display: flex;
  flex-direction: column;
  background: var(--bg, #f5f5f5);
}
</style>

<style>
* {
  box-sizing: border-box;
}
html {
  -webkit-tap-highlight-color: transparent;
}
body {
  margin: 0;
  font-family: system-ui, -apple-system, sans-serif;
  font-size: 16px;
  line-height: 1.5;
  color: #1a1a1a;
  -webkit-font-smoothing: antialiased;
}
/* Touch-friendly minimum target size */
a, button, [role="button"] {
  min-height: 44px;
  min-width: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
</style>
