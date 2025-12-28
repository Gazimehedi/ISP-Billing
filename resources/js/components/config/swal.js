import Swal from 'sweetalert2';

export const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

export const confirmAlert = (title = 'Are you sure?', text = "You won't be able to revert this!", confirmButtonText = 'Yes, delete it!') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        reverseButtons: true, // Cancel on left, Confirm on right
        customClass: {
            confirmButton: 'bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700',
            cancelButton: 'bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 mr-2'
        },
        // Remove SweetAlert2 default classes if using Tailwind completely, but usually mix works.
        // We will stick to standard style with some tweaks if needed.
    });
};
