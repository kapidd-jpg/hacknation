// PintarKuy — Halaman Kontak (form validation + FAQ accordion + reveal)
document.addEventListener('DOMContentLoaded', () => {

    // ---------- scroll reveal ----------
    const revealEls = document.querySelectorAll('.pk-reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach((el) => io.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    // ---------- form validation ----------
    const form = document.getElementById('kontakForm');
    const statusOk = document.getElementById('statusOk');
    const statusErr = document.getElementById('statusErr');

    if (!form) return;

    const fields = {
        nama:       { el: document.getElementById('fNama'),      rules: [v => v.trim() !== '' || 'Nama wajib diisi'] },
        email:      { el: document.getElementById('fEmail'),     rules: [v => v.trim() !== '' || 'Email wajib diisi', v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Format email tidak valid'] },
        subjek:     { el: document.getElementById('fSubjek'),    rules: [v => v.trim() !== '' || 'Subjek wajib diisi'] },
        pesan:      { el: document.getElementById('fPesan'),     rules: [v => v.trim() !== '' || 'Pesan wajib diisi', v => v.trim().length >= 10 || 'Pesan minimal 10 karakter'] },
    };

    const validate = () => {
        let valid = true;
        Object.values(fields).forEach((field) => {
            const wrapper = field.el.closest('.kontak-field');
            const errEl = wrapper.querySelector('.kontak-error');
            let msg = '';
            for (const rule of field.rules) {
                const result = rule(field.el.value);
                if (result !== true) { msg = result; break; }
            }
            wrapper.classList.toggle('has-error', msg !== '');
            errEl.textContent = msg;
            if (msg) valid = false;
        });
        return valid;
    };

    Object.values(fields).forEach((field) => {
        field.el.addEventListener('input', () => {
            const wrapper = field.el.closest('.kontak-field');
            if (wrapper.classList.contains('has-error')) validate();
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        statusOk.classList.remove('is-visible');
        statusErr.classList.remove('is-visible');

        if (!validate()) return;

        const kat = document.getElementById('fKategori');
        const btn = form.querySelector('.kontak-submit');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        try {
            const res = await fetch(window.pintarKuyContactUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.pintarKuyCsrf,
                },
                body: JSON.stringify({
                    nama: fields.nama.el.value,
                    email: fields.email.el.value,
                    subjek: fields.subjek.el.value,
                    kategori: kat ? kat.value : null,
                    pesan: fields.pesan.el.value,
                }),
            });

            if (!res.ok) throw new Error('http-' + res.status);

            statusOk.classList.add('is-visible');
            form.reset();
        } catch (err) {
            statusErr.classList.add('is-visible');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    });

    // ---------- FAQ accordion ----------
    document.querySelectorAll('.kontak-faq-q').forEach((q) => {
        q.addEventListener('click', () => {
            const item = q.closest('.kontak-faq-item');
            const isOpen = item.classList.contains('open');
            // close all
            document.querySelectorAll('.kontak-faq-item').forEach((i) => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        });
    });
});