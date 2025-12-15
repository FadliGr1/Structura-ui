export const initCounter = (wrapper) => {
  if (wrapper.dataset.vApp) return;

  const numberEl = wrapper.querySelector(".counter-number");
  if (!numberEl) return;

  const settings = JSON.parse(wrapper.dataset.settings || "{}");
  const start = settings.start || 0;
  const end = settings.end || 100;
  const duration = settings.duration || 2000;
  const separator = settings.separator;

  const format = (num) => {
    if (!separator) return Math.round(num).toString();
    return Math.round(num)
      .toString()
      .replace(/\B(?=(\d{3})+(?!\d))/g, separator);
  };

  const animate = () => {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      const easeProgress = 1 - Math.pow(1 - progress, 3);
      const currentVal = Math.floor(easeProgress * (end - start) + start);

      numberEl.textContent = format(currentVal);

      if (progress < 1) {
        window.requestAnimationFrame(step);
      } else {
        numberEl.textContent = format(end);
      }
    };
    window.requestAnimationFrame(step);
  };

  // Intersection Observer
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animate();
          observer.disconnect();
        }
      });
    },
    {threshold: 0.5}
  );

  observer.observe(wrapper);
  wrapper.dataset.vApp = "true";
};
