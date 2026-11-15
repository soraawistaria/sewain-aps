const formatRupiah = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number.isFinite(value) ? value : 0);

const parseDate = (value) => {
    if (!value) {
        return null;
    }

    const date = new Date(`${value}T00:00:00`);

    return Number.isNaN(date.getTime()) ? null : date;
};

const formatTanggal = (value) => {
    const date = parseDate(value);

    return date
        ? date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
        : '—';
};

const hitungDurasiHari = (mulai, selesai) => {
    const tanggalMulai = parseDate(mulai);
    const tanggalSelesai = parseDate(selesai);

    if (!tanggalMulai || !tanggalSelesai || tanggalSelesai < tanggalMulai) {
        return null;
    }

    return Math.floor((tanggalSelesai - tanggalMulai) / 86_400_000) + 1;
};

const initMobileMenus = () => {
    document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
        const target = document.querySelector(button.dataset.menuToggle);

        if (!target) {
            return;
        }

        button.addEventListener('click', () => {
            target.classList.toggle('hidden');
            button.setAttribute('aria-expanded', String(!target.classList.contains('hidden')));
        });
    });

    document.querySelectorAll('[data-admin-sidebar-toggle]').forEach((button) => {
        const sidebar = document.querySelector('[data-admin-sidebar]');
        const overlay = document.querySelector('[data-admin-overlay]');

        if (!sidebar) {
            return;
        }

        const toggle = () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay?.classList.toggle('hidden');
        };

        button.addEventListener('click', toggle);
        overlay?.addEventListener('click', toggle);
    });
};

const initFlashMessages = () => {
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        const close = () => {
            flash.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(() => flash.remove(), 250);
        };

        flash.querySelector('[data-flash-close]')?.addEventListener('click', close);
        window.setTimeout(close, 5000);
    });
};

const initConfirmations = () => {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
};

const initOrderCalculator = () => {
    document.querySelectorAll('[data-order-form]').forEach((form) => {
        const harga = Number(form.dataset.harga);
        const mulai = form.querySelector('[name="tanggal_mulai"]');
        const selesai = form.querySelector('[name="tanggal_selesai"]');
        const total = form.querySelector('[data-total]');
        const durasi = form.querySelector('[data-durasi]');

        if (!mulai || !selesai || !total) {
            return;
        }

        const update = () => {
            const jumlahHari = hitungDurasiHari(mulai.value, selesai.value);

            if (jumlahHari === null) {
                total.textContent = '—';
                if (durasi) {
                    durasi.textContent = '—';
                }

                return;
            }

            if (durasi) {
                durasi.textContent = `${jumlahHari} hari`;
            }

            total.textContent = formatRupiah(jumlahHari * harga);
        };

        mulai.addEventListener('change', update);
        selesai.addEventListener('change', update);

        if (mulai.value) {
            selesai.min = mulai.value;
        }

        mulai.addEventListener('change', () => {
            selesai.min = mulai.value;
        });

        update();
    });
};

const initPaymentModal = () => {
    const form = document.querySelector('[data-order-form]');
    const modal = document.querySelector('[data-payment-modal]');

    if (!form || !modal) {
        return;
    }

    const harga = Number(form.dataset.harga);
    const mulai = form.querySelector('[name="tanggal_mulai"]');
    const selesai = form.querySelector('[name="tanggal_selesai"]');
    const confirmButton = modal.querySelector('[data-payment-confirm]');
    const closeButtons = modal.querySelectorAll('[data-payment-close]');
    const ringkasan = {
        barang: modal.querySelector('[data-modal-barang]'),
        foto: modal.querySelector('[data-modal-foto]'),
        tanggal: modal.querySelector('[data-modal-tanggal]'),
        durasi: modal.querySelector('[data-modal-durasi]'),
        harga: modal.querySelector('[data-modal-harga]'),
        total: modal.querySelector('[data-modal-total]'),
    };

    let sudahDikonfirmasi = false;

    const buka = () => {
        const jumlahHari = hitungDurasiHari(mulai?.value, selesai?.value);

        if (ringkasan.barang) {
            ringkasan.barang.textContent = form.dataset.nama ?? '';
        }

        if (ringkasan.foto && form.dataset.foto) {
            ringkasan.foto.src = form.dataset.foto;
            ringkasan.foto.alt = form.dataset.nama ?? '';
        }

        if (ringkasan.tanggal) {
            ringkasan.tanggal.textContent = `${formatTanggal(mulai?.value)} → ${formatTanggal(selesai?.value)}`;
        }

        if (ringkasan.durasi) {
            ringkasan.durasi.textContent = jumlahHari === null ? '—' : `${jumlahHari} hari`;
        }

        if (ringkasan.harga) {
            ringkasan.harga.textContent = formatRupiah(harga);
        }

        if (ringkasan.total) {
            ringkasan.total.textContent = jumlahHari === null ? '—' : formatRupiah(jumlahHari * harga);
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        confirmButton?.focus();
    };

    const tutup = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    form.addEventListener('submit', (event) => {
        if (sudahDikonfirmasi) {
            return;
        }

        event.preventDefault();

        if (!form.reportValidity() || hitungDurasiHari(mulai?.value, selesai?.value) === null) {
            return;
        }

        buka();
    });

    confirmButton?.addEventListener('click', () => {
        sudahDikonfirmasi = true;
        confirmButton.disabled = true;
        confirmButton.classList.add('pointer-events-none', 'opacity-70');
        confirmButton.textContent = 'Memproses…';
        tutup();
        form.requestSubmit();
    });

    closeButtons.forEach((button) => button.addEventListener('click', tutup));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            tutup();
        }
    });
};

const initPasswordToggles = () => {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = document.querySelector(button.dataset.passwordToggle);

        if (!input) {
            return;
        }

        button.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initMobileMenus();
    initFlashMessages();
    initConfirmations();
    initOrderCalculator();
    initPaymentModal();
    initPasswordToggles();
});
