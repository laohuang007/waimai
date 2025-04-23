import { createApp } from 'vue'
import App from './App.vue'
import 'vant/es/toast/style'
import router from './router'
import { Locale } from 'vant';

const messages = {
    'zh-CN': {
      vanPicker: {
        confirm: 'OK', // 将'确认'修改为'关闭'
      },
    },
  };
Locale.add(messages);
const app = createApp(App)
app.use(router)
app.mount('#app')
