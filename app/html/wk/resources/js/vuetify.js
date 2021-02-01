import Vue from "vue";
import Vuetify from "vuetify";

import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify);

const vuetify = {
    iconfont: 'mdi',
    theme: {
        themes: {
            light: {
                primary: colors.green.darken1,
                secondary: colors.green.lighten4,
                accent: colors.indigo.base,
                info: colors.blue.lighten1,
            },
        },
    },
};

export default new Vuetify(vuetify);
