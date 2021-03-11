import Vue from 'vue';
import VueBreadcrumbs from 'vue-2-breadcrumbs'

Vue.use(VueBreadcrumbs, {
    template:
        `<div>
          <v-breadcrumbs class='breadcrumb-item active' :items="items" />
        </div>`,
    computed: {
        items() {
            return this.$breadcrumbs.map((crumb, i) => {
                return {
                    text: this.getBreadcrumb(crumb.meta.breadcrumb),
                    disabled: this.$breadcrumbs.length - 1 === i,
                    to: this.getPath(crumb)
                };
            });
        }
    }
});

export default VueBreadcrumbs;
