<template>
  <svg class="loading-spinner">
    <circle
      :cx="circlePositions[index] && circlePositions[index].x"
      :cy="circlePositions[index] && circlePositions[index].y"
      :r="item.radius"
      :fill="item.color"
      v-for="(item, index) in circles"
      :key="index"/>
  </svg>
</template>

<script>
const CENTER_X = 50;
const CENTER_Y = 50;
const RADIUS = 20;

function positionOnCircle(radius, angle, center) {
  return {
    x: center.x + (radius * Math.cos(toRadians(angle))),
    y: center.y + (radius * Math.sin(toRadians(angle)))
  };
};

function toRadians(angle) {
  return angle * Math.PI / 180;
};

function calculatePositions(component) {
  let angleIncrement = 360 / component.circles.length;
  let positions = {};
  component.circles.forEach((circle, index) => {
    positions[index] = positionOnCircle(
      RADIUS,
      angleIncrement * index,
      {x: CENTER_X, y: CENTER_Y}
    )
  });
  return positions;
}

export default {
  data() {
    return {
      circles: [
        {color: '#F7B570', radius: 0},
        {color: '#F28E73', radius: 0},
        {color: '#F18BA6', radius: 0},
        {color: '#9790CB', radius: 0},
        {color: '#9FADD1', radius: 0},
        {color: '#ABC4D3', radius: 0},
        {color: '#C7D2C9', radius: 0},
        {color: '#FDE9D4', radius: 0},
        {color: '#FBE4DF', radius: 0},
      ],
      counter: 0,
      interval: null
    }
  },
  computed: {
    circlePositions: calculatePositions
  },
  created() {
    this.interval = setInterval(() => {
      this.counter++;
      this.circles = this.circles.map((item, index) => ({
        ...item,
        radius: (this.counter + index) % 8
      }));
    }, 70);
  },
  destroyed() {
    clearInterval(this.interval);
  }
}
</script>

<style scoped>
.loading-spinner {
  width: 100px;
  height: 100px;
}
</style>
