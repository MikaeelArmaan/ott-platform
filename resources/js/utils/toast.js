window.toast = function(message, icon = 'success') {

    const colorMap = {
        success: 'text-green-400 outline-green-500/40',
        error: 'text-red-400 outline-red-500/40',
        warning: 'text-yellow-400 outline-yellow-500/40',
        info: 'text-blue-400 outline-blue-500/40'
    };

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        showClass: {
            popup: 'animate-toast-in'
        },
        hideClass: {
            popup: 'animate-toast-out'
        },
        customClass: {
            popup: `!bg-zinc-900/40 !backdrop-blur-xl ${colorMap[icon]} !outline shadow-lg rounded-xl`
        }
    });
};