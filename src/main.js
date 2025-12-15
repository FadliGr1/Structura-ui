import {createApp} from "vue";

// 1. Vue Components
import ChartElement from "./components/charts/ChartElement.vue";
import BeforeAfter from "./components/sliders/BeforeAfter.vue";
import Hotspots from "./components/hotspots/Hotspots.vue";
import LottieAnimation from "./components/lottie/LottieAnimation.vue";
import Marquee from "./components/marquee/Marquee.vue";
import PricingToggle from "./components/pricing/PricingToggle.vue";

// 2. Vanilla JS Modules (Ringan)
import {initTypewriter} from "./components/text/typewriter.js"; // Asumsi pakai JS
import {initCounter} from "./components/counter/counter.js";
import {initTilt} from "./components/tilt/tilt.js";

// 3. Styles
import "./scss/style.scss";

/**
 * Helper: Mount Vue Component safely
 */
const mountComponent = (el, Component, label) => {
  if (el.dataset.vApp) return;

  try {
    const settings = JSON.parse(el.dataset.settings || "{}");
    const app = createApp(Component, {settings});
    app.mount(el);
    el.dataset.vApp = "true";
  } catch (error) {
    console.error(`❌ [Structura UI] ${label} Error:`, error);
  }
};

/**
 * Main Init Function
 */
window.initStructuraUI = () => {
  // 1. Chart
  document.querySelectorAll(".structura-vue-chart").forEach((el) => {
    mountComponent(el, ChartElement, "Chart");
  });

  // 2. Before/After Slider
  document.querySelectorAll(".structura-vue-before-after").forEach((el) => {
    mountComponent(el, BeforeAfter, "Before/After");
  });

  // 3. Hotspots
  document.querySelectorAll(".structura-vue-hotspots").forEach((el) => {
    mountComponent(el, Hotspots, "Hotspots");
  });

  // 4. Lottie
  document.querySelectorAll(".structura-vue-lottie").forEach((el) => {
    mountComponent(el, LottieAnimation, "Lottie");
  });

  // 5. Marquee
  document.querySelectorAll(".structura-vue-marquee").forEach((el) => {
    mountComponent(el, Marquee, "Marquee");
  });

  // 6. Pricing Toggle (Vue Part Only)
  document.querySelectorAll(".pricing-reactive-area").forEach((el) => {
    mountComponent(el, PricingToggle, "Pricing Toggle");
  });

  // 7. Counter (Vanilla JS)
  document.querySelectorAll(".structura-vue-counter").forEach((el) => {
    if (typeof initCounter === "function") initCounter(el);
  });

  // 8. Tilt Image (Vanilla JS)
  document.querySelectorAll(".structura-vue-tilt").forEach((el) => {
    if (typeof initTilt === "function") initTilt(el);
  });

  // 9. Typewriter (Vanilla JS Logic pada wrapper)
  document.querySelectorAll(".structura-vue-typewriter").forEach((el) => {
    if (typeof initTypewriter === "function") initTypewriter(el);
  });
};

/**
 * Boot & Watcher
 */
document.addEventListener("DOMContentLoaded", () => {
  window.initStructuraUI();
});

// Auto-detect elemen baru di Builder (AJAX)
setInterval(() => {
  const unmounted = document.querySelector(
    ".structura-vue-chart:not([data-v-app]), " +
      ".structura-vue-before-after:not([data-v-app]), " +
      ".structura-vue-hotspots:not([data-v-app]), " +
      ".structura-vue-lottie:not([data-v-app]), " +
      ".structura-vue-marquee:not([data-v-app]), " +
      ".pricing-reactive-area:not([data-v-app]), " +
      ".structura-vue-counter:not([data-v-app]), " +
      ".structura-vue-tilt:not([data-v-app])"
  );

  if (unmounted) {
    window.initStructuraUI();
  }
}, 1000);

console.log("🚀 Structura UI: Full Loaded");
