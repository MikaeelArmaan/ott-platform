import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

new Swiper('.swiper', {
    modules: [Autoplay, Pagination, EffectFade],
    loop: true,
    effect: 'fade',
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
});
