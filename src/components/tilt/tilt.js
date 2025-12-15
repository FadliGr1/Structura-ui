import VanillaTilt from "vanilla-tilt";

export const initTilt = (wrapper) => {
  if (wrapper.dataset.vApp) return;

  const target = wrapper.querySelector(".tilt-inner");
  if (!target) return;

  const settings = JSON.parse(wrapper.dataset.settings || "{}");

  // Init Vanilla Tilt
  VanillaTilt.init(target, {
    max: settings.max || 15,
    perspective: settings.perspective || 1000,
    speed: settings.speed || 400,
    scale: settings.scale || 1.05,
    glare: settings.glare !== false, // default true
    "max-glare": settings.maxGlare || 0.5,
    reverse: settings.reverse || false,
  });

  wrapper.dataset.vApp = "true";
};
