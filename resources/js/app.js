import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import Swal from 'sweetalert2';

Alpine.plugin(collapse)

window.Swal = Swal;
window.Alpine = Alpine;

Alpine.start();
