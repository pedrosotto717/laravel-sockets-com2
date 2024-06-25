import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader

export default createVuetify({
    components,
    directives,
    theme: {
        themes: {
          light: {
            dark: true,
          },
        },
      },
});