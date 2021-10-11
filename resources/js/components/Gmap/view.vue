<template>
  <div id="app" :style="iframe.appStyle">
    <div id="iframe-wrapper" :style="iframe.wrapperStyle">
      <iframe
        :src="iframe.src"
        frameborder="0"
        :style="iframe.ifstyle"
        width="100%"
        height="100%"
      ></iframe>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    var APP_URL = "{{url('/')}}";
    return {
      loaded: false,
      iframe: {
        src: "http://dsi.salespad.lk/tgmapso",
        wrapperStyle: {
          width: "100%",
          "margin-bottom": "150px",
        },
        ifstyle: {
          "margin-top": "-50px",
          "margin-bottom": "150px",
          margin: "0px",
        },
        appStyle: {
          "margin-bottom": "100px",
        },
      },
    };
  },
};
</script>