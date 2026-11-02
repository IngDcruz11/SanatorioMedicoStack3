<script setup>
import { ref } from 'vue'
import { authService } from '../Services/authService.js'
const emit = defineEmits(['authenticated'])
const email = ref(''), password = ref(''), busy = ref(false), errors = ref({}), message = ref('')
async function login() {
  if (busy.value) return
  busy.value = true; errors.value = {}; message.value = ''
  try { const user = await authService.login({ email: email.value.trim(), password: password.value }); password.value = ''; emit('authenticated', user) }
  catch (error) {
    errors.value = error.response?.data?.errors ?? {}
    message.value = error.response?.status === 422 ? 'Revisa tus credenciales.' : error.response?.status === 429 ? 'Demasiados intentos. Espera un momento antes de volver a intentar.' : error.response ? 'No se pudo iniciar sesión. Intenta de nuevo.' : 'No se pudo conectar con el servidor.'
  } finally { busy.value = false }
}
</script>
<template>
  <main class="login-page"><section class="login-card" aria-labelledby="login-title">
    <p class="eyebrow">Sanatorio Médico</p><h1 id="login-title">Iniciar sesión</h1><p class="login-subtitle">Accede con tu cuenta para administrar el sistema.</p>
    <p v-if="message" class="login-error" role="alert">{{ message }}</p>
    <form @submit.prevent="login"><fieldset :disabled="busy">
      <label for="login-email">Correo electrónico</label><input id="login-email" v-model="email" type="email" required maxlength="254" autocomplete="username" :aria-invalid="!!errors.email" :aria-describedby="errors.email ? 'login-email-error' : undefined" />
      <span v-if="errors.email" id="login-email-error" class="login-field-error">{{ errors.email.join(' ') }}</span>
      <label for="login-password">Contraseña</label><input id="login-password" v-model="password" type="password" required maxlength="1024" autocomplete="current-password" :aria-invalid="!!errors.password" :aria-describedby="errors.password ? 'login-password-error' : undefined" />
      <span v-if="errors.password" id="login-password-error" class="login-field-error">{{ errors.password.join(' ') }}</span>
      <button class="login-submit" type="submit">{{ busy ? 'Iniciando sesión…' : 'Ingresar' }}</button>
    </fieldset></form>
  </section></main>
</template>
<style scoped>
.login-page { min-height: 100vh; display: grid; place-items: center; padding: 24px; background: linear-gradient(135deg,#f4f7f8,#e2f6f2); }
.login-card { width: min(440px,100%); background: white; padding: clamp(24px,5vw,40px); border-radius: 20px; box-shadow: 0 16px 60px #18323a14; border: 1px solid #dce7e9; }
.login-subtitle { color: #557078; line-height: 1.5; margin-bottom: 28px; }
fieldset { display: flex; flex-direction: column; gap: 10px; }
label { font-weight: 600; margin-top: 10px; }
input { width: 100%; }
input[aria-invalid="true"] { border-color: #b42318; }
.login-submit { background: #008577; color: white; border-color: #008577; margin-top: 20px; padding: 12px; font-weight: 700; }
.login-submit:hover { background: #006d62; }
.login-error { padding: 12px; background: #fff1f1; color: #8f1b13; border-radius: 8px; }
.login-field-error { color: #b42318; font-size: .85rem; }
</style>
