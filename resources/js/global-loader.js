import NProgress from "nprogress";
import "nprogress/nprogress.css";

window.NProgress = NProgress;

NProgress.configure({
    showSpinner: true,
    trickleSpeed: 800,
    trickleRate: 0.02,
    trickle: true,
    easing: 'ease',
});
