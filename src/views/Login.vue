<template>
  <div class="login-container">
    <div class="card login-card">
      <h1 class="brand">MadaaQ</h1>
      <h2 style="text-align: center; margin-bottom: 30px;">تسجيل الدخول للفيندور</h2>
      
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label class="form-label">البريد الإلكتروني</label>
          <input type="email" v-model="email" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label class="form-label">كلمة المرور</label>
          <input type="password" v-model="password" class="form-input" required />
        </div>
        
        <div v-if="error" class="error-msg">{{ error }}</div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;" :disabled="loading">
          {{ loading ? 'جاري الدخول...' : 'دخول' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/auth';

const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  
  const success = await authStore.login({ email: email.value, password: password.value });
  
  if (success) {
    router.push('/');
  } else {
    error.value = 'البيانات غير صحيحة، يرجى المحاولة مرة أخرى.';
  }
  
  loading.value = false;
};
</script>

<style scoped>
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: var(--bg-main);
}
.login-card {
  width: 100%;
  max-width: 400px;
  padding: 40px;
}
.error-msg {
  color: var(--danger);
  font-size: 14px;
  margin-bottom: 15px;
  text-align: center;
}
</style>
