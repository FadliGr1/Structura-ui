<script setup>
import {ref, onMounted, watch, shallowRef} from "vue";
import Chart from "chart.js/auto";

/**
 * Props Definition
 */
const props = defineProps({
  settings: {type: Object, default: () => ({})},
});

/**
 * Component References
 */
const chartCanvas = ref(null);
const chartInstance = shallowRef(null);

/**
 * Main Render Logic
 * Handles setup for both Cartesian (Bar/Line) and Radial (Pie/Doughnut) charts.
 */
const renderChart = () => {
  if (!chartCanvas.value) return;

  // Cleanup existing chart instance to prevent memory leaks
  if (chartInstance.value) {
    chartInstance.value.stop();
    chartInstance.value.destroy();
  }

  // Extract Configuration
  const globalType = props.settings.type || "bar";
  const rawData = props.settings.data || [];
  const legendPos = props.settings.legend || "top";

  // Process Basic Data Arrays
  const labels = rawData.map((d) => String(d.label || ""));
  const values = rawData.map((d) => Number(d.value || 0));

  // Safe Color Mapping
  const colors = rawData.map((d) => {
    return d.color && typeof d.color === "string" ? d.color : "#cbd5e1";
  });

  /**
   * Determine Chart Mode
   */
  const isRadial = ["pie", "doughnut"].includes(globalType);
  const datasets = [];

  // Default Options
  const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: legendPos !== "false",
        position: legendPos !== "false" ? legendPos : "top",
        labels: {
          usePointStyle: true, // Modern rounded legend points
          padding: 20,
        },
      },
      tooltip: {
        mode: "index",
        intersect: false,
        backgroundColor: "rgba(255, 255, 255, 0.9)",
        titleColor: "#1f2937",
        bodyColor: "#4b5563",
        borderColor: "#e5e7eb",
        borderWidth: 1,
        padding: 10,
      },
    },
    animation: {
      duration: 800,
      easing: "easeOutQuart",
    },
  };

  if (isRadial) {
    // --- MODE 1: RADIAL (Pie / Doughnut) ---
    datasets.push({
      type: globalType,
      label: "Dataset",
      data: values,
      backgroundColor: colors,
      borderColor: "#ffffff",
      borderWidth: 2,
      hoverOffset: 8,
    });

    // Remove scales for radial charts
    options.scales = {};
  } else {
    // --- MODE 2: CARTESIAN (Bar / Line / Mixed) ---

    // Add Axis configurations
    options.scales = {
      y: {
        beginAtZero: true,
        grid: {color: "#f3f4f6"}, // Light gray grid
        ticks: {color: "#9ca3af"},
      },
      x: {
        grid: {display: false}, // Cleaner look without X grid
        ticks: {color: "#6b7280"},
      },
    };

    // Initialize Dataset Containers
    const defaultSet = {
      type: globalType,
      label: "Main Data",
      data: [],
      backgroundColor: [],
      borderColor: [],
      borderWidth: 0, // Cleaner bars without borders
      borderRadius: globalType === "bar" ? 6 : 0, // Modern rounded corners
      order: 2,
    };

    const lineSet = {
      type: "line",
      label: "Trend",
      data: [],
      backgroundColor: "transparent",
      borderColor: "#000000", // Will be overwritten
      borderWidth: 3,
      tension: 0.4, // Smooth bezier curve
      pointRadius: 5,
      pointHoverRadius: 7,
      pointBackgroundColor: "#ffffff",
      pointBorderWidth: 2,
      order: 1, // Layer on top
    };

    let hasLine = false;

    // Distribute Repeater Data into Layers
    rawData.forEach((d) => {
      const itemType = d.type === "global" ? globalType : d.type;
      const itemColor = d.color && typeof d.color === "string" ? d.color : "#3b82f6";

      if (itemType === "line") {
        // Add to Overlay Line Layer
        lineSet.data.push(Number(d.value || 0));
        lineSet.borderColor = itemColor; // Last line item dictates color
        lineSet.pointBorderColor = itemColor;

        // Push Null to Main Layer
        defaultSet.data.push(null);
        defaultSet.backgroundColor.push("transparent");
        hasLine = true;
      } else {
        // Add to Main Bar Layer
        defaultSet.data.push(Number(d.value || 0));
        defaultSet.backgroundColor.push(itemColor);

        // Push Null to Overlay Layer
        lineSet.data.push(null);
      }
    });

    // Finalize Datasets
    if (hasLine) {
      datasets.push(defaultSet);
      datasets.push(lineSet);
    } else {
      // Simple Single Dataset (No Splitting)
      datasets.push({
        type: globalType,
        label: "Data",
        data: values,
        backgroundColor: colors,
        borderColor: colors,
        borderWidth: 0,
        borderRadius: globalType === "bar" ? 6 : 0,
      });
    }
  }

  // Create Chart
  const ctx = chartCanvas.value.getContext("2d");

  chartInstance.value = new Chart(ctx, {
    data: {
      labels: labels,
      datasets: datasets,
    },
    options: options,
  });
};

/**
 * Lifecycle Hooks
 */
onMounted(() => {
  renderChart();
});

watch(
  () => props.settings,
  () => {
    renderChart();
  },
  {deep: true}
);
</script>

<template>
  <div class="w-full h-full p-6 bg-white rounded-xl border border-gray-100 shadow-sm relative">
    <div class="relative w-full h-[320px]">
      <canvas ref="chartCanvas" role="img" aria-label="Interactive Data Chart"> </canvas>
    </div>
  </div>
</template>
