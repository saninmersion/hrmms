import VueLightbox from "./vue-lightbox.vue";

VueLightbox.install = function(Vue) {
    if (VueLightbox.installed) return;

    Vue.component(VueLightbox.name, VueLightbox);
};

if (typeof window !== "undefined" && window.Vue) {
    window.Vue.use(VueLightbox);
}

export default VueLightbox;
