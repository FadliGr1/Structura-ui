<template>
  <div ref="container" class="lottie-container w-full h-auto" @mouseenter="handleHover(true)" @mouseleave="handleHover(false)" @click="handleClick"></div>
</template>

<script setup>
import {ref, onMounted, onUnmounted, watch} from "vue";
import lottie from "lottie-web";

/**
 * Props Definition
 */
const props = defineProps({
  settings: {
    type: Object,
    required: true,
    default: () => ({
      src: "",
      trigger: "viewport", // 'autoplay', 'viewport', 'hover', 'click'
      loop: true,
      speed: 1,
    }),
  },
});

/**
 * References & State
 */
const container = ref(null);
let animationInstance = null;
let observer = null;
let isPlaying = false;

/**
 * Initialize Lottie
 */
const initAnimation = () => {
  if (!container.value || !props.settings.src) return;

  // Cleanup old instance if exists (handling reactive updates)
  if (animationInstance) {
    animationInstance.destroy();
  }

  // Configure Lottie
  animationInstance = lottie.loadAnimation({
    container: container.value,
    renderer: "svg",
    loop: props.settings.loop,
    autoplay: false, // We handle playback manually based on triggers
    path: props.settings.src,
  });

  // Set Speed
  animationInstance.setSpeed(props.settings.speed);

  // Handle Initial Trigger
  handleInitialTrigger();
};

/**
 * Logic: Handle Triggers
 */
const handleInitialTrigger = () => {
  const trigger = props.settings.trigger;

  if (trigger === "autoplay") {
    play();
  } else if (trigger === "viewport") {
    setupIntersectionObserver();
  }
  // Hover & Click are handled by template events
};

/**
 * Play/Pause Helpers
 */
const play = () => {
  if (animationInstance) {
    animationInstance.play();
    isPlaying = true;
  }
};

const pause = () => {
  if (animationInstance) {
    animationInstance.pause();
    isPlaying = false;
  }
};

const stop = () => {
  if (animationInstance) {
    animationInstance.stop();
    isPlaying = false;
  }
};

/**
 * Logic: Viewport Trigger (Intersection Observer)
 */
const setupIntersectionObserver = () => {
  if (observer) observer.disconnect();

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          play();
        } else {
          pause(); // Optional: Pause when scrolling out of view to save performance
        }
      });
    },
    {threshold: 0.2}
  ); // Play when 20% visible

  if (container.value) {
    observer.observe(container.value);
  }
};

/**
 * Logic: Hover Trigger
 */
const handleHover = (isHovering) => {
  if (props.settings.trigger !== "hover") return;

  if (isHovering) {
    play();
  } else {
    // Behavior: Stop (reset) or Pause? Usually Stop/Reset is better for hover effects.
    stop();
  }
};

/**
 * Logic: Click Trigger
 */
const handleClick = () => {
  if (props.settings.trigger !== "click") return;

  if (isPlaying) {
    pause();
  } else {
    play();
  }
};

/**
 * Watchers & Lifecycle
 */
// Re-init if source changes in Bricks builder
watch(
  () => props.settings.src,
  () => {
    initAnimation();
  }
);

watch(
  () => props.settings.speed,
  (newSpeed) => {
    if (animationInstance) animationInstance.setSpeed(newSpeed);
  }
);

onMounted(() => {
  initAnimation();
});

onUnmounted(() => {
  if (animationInstance) animationInstance.destroy();
  if (observer) observer.disconnect();
});
</script>

<style scoped>
/* Ensure SVG scales correctly */
.lottie-container :deep(svg) {
  width: 100% !important;
  height: 100% !important;
  display: block;
}
</style>
