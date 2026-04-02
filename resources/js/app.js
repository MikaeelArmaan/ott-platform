import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

import Alpine from 'alpinejs';
window.Alpine = Alpine;

// ✅ LOAD ALL MODULES FIRST
import './modules/hero-swiper';
import './modules/hls-init';
import './utils/toast';
import './modules/wishlist';
import './global-loader';
import './modules/row-swiper';
import './modules/load-more';
import './modules/datatable-init';
import './modules/content';
import './modules/image-upload';
import './modules/select2';
import './modules/media-manager'; // 🔥 IMPORTANT
import './modules/video-like';
import './modules/video-share';

// ✅ START ALPINE LAST
Alpine.start();