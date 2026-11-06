<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Notifikasi Flash Session (SweetAlert2 untuk Hasil Tindakan Krusial)
    @if(session('success') && !session('login_success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 3500,
            timerProgressBar: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#0f172a',
            customClass: {
                popup: 'rounded-2xl shadow-2xl p-6',
                title: 'text-lg font-bold text-slate-900',
                htmlContainer: 'text-xs text-slate-600',
                confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: @json(session('error')),
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#0f172a',
            customClass: {
                popup: 'rounded-2xl shadow-2xl p-6',
                title: 'text-lg font-bold text-slate-900',
                htmlContainer: 'text-xs text-slate-600',
                confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
            }
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Pemberitahuan',
            text: @json(session('info')),
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#0f172a',
            customClass: {
                popup: 'rounded-2xl shadow-2xl p-6',
                title: 'text-lg font-bold text-slate-900',
                htmlContainer: 'text-xs text-slate-600',
                confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
            }
        });
    @endif

    // 2. Interceptor Konfirmasi Tindakan Krusial (Menghapus, Mengedit, Menambah)
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        // Abaikan jika form sudah dikonfirmasi oleh SweetAlert2
        if (form.dataset.swalConfirmed === 'true') {
            return;
        }

        // Abaikan form pencarian/filter bertipe GET atau data-no-confirm
        const method = (form.getAttribute('method') || 'GET').toUpperCase();
        if (method === 'GET' || form.hasAttribute('data-no-confirm')) {
            return;
        }

        // Jalankan validasi HTML5 form terlebih dahulu
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const isDelete = form.classList.contains('form-delete') || 
                         form.querySelector('input[name="_method"][value="DELETE"]') ||
                         form.action.includes('destroy') ||
                         form.action.includes('kick');

        const isEdit = form.classList.contains('form-edit') || 
                       form.querySelector('input[name="_method"][value="PUT"]') ||
                       form.querySelector('input[name="_method"][value="PATCH"]') ||
                       form.action.includes('update') ||
                       (window.location.pathname.includes('/edit') && method === 'POST');

        const isKoreksi = form.action.includes('simpanSemua') || form.action.includes('koreksi');

        const isCreate = !isDelete && !isEdit && !isKoreksi && (
                         form.classList.contains('form-create') || 
                         form.action.includes('store') ||
                         (window.location.pathname.includes('/create') && method === 'POST'));

        // A. Tindakan Menghapus Data (Krusial - Warning Merah)
        if (isDelete) {
            e.preventDefault();
            const title = form.dataset.confirmTitle || 'Konfirmasi Penghapusan';
            const message = form.dataset.confirmMessage || 'Apakah Anda yakin ingin menghapus data ini?';
            const confirmBtn = form.dataset.confirmBtn || 'Ya, Hapus Data!';

            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtn,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    htmlContainer: 'text-xs text-slate-600',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold shadow-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }

        // B. Tindakan Koreksi & Penilaian Siswa
        if (isKoreksi) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Hasil Penilaian?',
                text: 'Seluruh skor dan catatan koreksi akan disimpan serta memperbarui rekapitulasi nilai siswa.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan Nilai!',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    htmlContainer: 'text-xs text-slate-600',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold shadow-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }

        // C. Tindakan Meng-edit / Memperbarui Data (Krusial - Question Dark Navy)
        if (isEdit) {
            e.preventDefault();
            const title = form.dataset.confirmTitle || 'Simpan Perubahan Data?';
            const message = form.dataset.confirmMessage || 'Pastikan seluruh perubahan data yang Anda masukkan sudah benar sebelum disimpan.';
            const confirmBtn = form.dataset.confirmBtn || 'Ya, Perbarui!';

            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtn,
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    htmlContainer: 'text-xs text-slate-600',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold shadow-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }

        // D. Tindakan Menambahkan Data Baru (Krusial - Question Dark Navy)
        if (isCreate) {
            e.preventDefault();
            const title = form.dataset.confirmTitle || 'Tambahkan Data Baru?';
            const message = form.dataset.confirmMessage || 'Data yang Anda masukkan akan segera disimpan.';
            const confirmBtn = form.dataset.confirmBtn || 'Ya, Tambahkan!';

            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtn,
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    htmlContainer: 'text-xs text-slate-600',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold shadow-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.swalConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }
    }, true);
});
</script>
