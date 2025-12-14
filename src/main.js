import {createApp} from "vue";

import ChartElement from "./components/charts/ChartElement.vue";
import BeforeAfter from "./components/sliders/BeforeAfter.vue";
import Hotspots from "./components/hotspots/Hotspots.vue";

import "./scss/style.scss";

const mountComponent = (el, Component, label) => {
  if (el.dataset.vApp) return;
  try {
    const settings = JSON.parse(el.dataset.settings || "{}");
    const app = createApp(Component, {settings});
    app.mount(el);
    el.dataset.vApp = "true";
  } catch (error) {
    console.error(`❌ ${label} Mount Error:`, error);
  }
};

window.initStructuraUI = () => {
  const charts = document.querySelectorAll(".structura-vue-chart");
  charts.forEach((el) => mountComponent(el, ChartElement, "Chart"));

  const sliders = document.querySelectorAll(".structura-vue-slider");
  sliders.forEach((el) => mountComponent(el, BeforeAfter, "Slider"));

  const hotspots = document.querySelectorAll(".structura-vue-hotspots");
  hotspots.forEach((el) => mountComponent(el, Hotspots, "Hotspots"));
};

document.addEventListener("DOMContentLoaded", () => {
  window.initStructuraUI();
});

setInterval(() => {
  const unmounted = document.querySelector(".structura-vue-chart:not([data-v-app]), .structura-vue-slider:not([data-v-app]), .structura-vue-hotspots:not([data-v-app])");
  if (unmounted) window.initStructuraUI();
}, 1000);

console.log("🚀 Structura UI: Ready");
