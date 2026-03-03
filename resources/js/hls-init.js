import Hls from 'hls.js';


window.initHlsPlayer = function(videoEl, hlsUrl) {
  if (!hlsUrl) return;

  if (videoEl.canPlayType('application/vnd.apple.mpegurl')) {
    // Safari native HLS
    videoEl.src = hlsUrl;
    return;
  }

  if (Hls.isSupported()) {
    const hls = new Hls();
    hls.loadSource(hlsUrl);
    hls.attachMedia(videoEl);
    window.__hls = hls;
  } else {
    console.warn('HLS not supported in this browser.');
  }
}