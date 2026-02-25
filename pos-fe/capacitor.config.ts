import type { CapacitorConfig } from '@capacitor/cli'

const config: CapacitorConfig = {
  appId: 'com.pos.mobile',
  appName: 'POS Mobile',
  webDir: '.output/public',
  server: {
    // For live reload when developing with Capacitor
    // url: 'http://localhost:3000',
    // cleartext: true,
  },
}

export default config
