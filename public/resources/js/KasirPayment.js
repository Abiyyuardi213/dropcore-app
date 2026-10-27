(() => {
    'use strict';
    const form = document.getElementById('paymentForm');
    if (!form) return;
    const total = window.cashierPaymentConfig.total;
    const amount = document.getElementById('paymentAmount');
    const finish = document.getElementById('finishPayment');
    const message = document.getElementById('paymentMessage');
    let sending = false;
    const money = cents => 'Rp ' + (cents / 100).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const paidCents = () => {
        if (!/^\d+(\.\d{1,2})?$/.test(amount.value)) return null;
        const [whole, fraction = ''] = amount.value.split('.');
        const value = Number(whole) * 100 + Number(fraction.padEnd(2, '0'));
        return Number.isSafeInteger(value) ? value : null;
    };
    function update() {
        const method = form.querySelector('input[name="sumber_id"]:checked');
        document.getElementById('paymentDetails').hidden = !method;
        document.getElementById('paymentPlaceholder').hidden = !!method;
        if (!method) { finish.disabled = true; return; }
        document.getElementById('paymentMethodName').textContent = method.dataset.name;
        amount.readOnly = method.dataset.type !== 'tunai';
        if (amount.readOnly) amount.value = (total / 100).toFixed(2);
        document.getElementById('paymentHelp').textContent = amount.readOnly ? 'Pastikan pembayaran nontunai sebesar tagihan sudah diterima.' : 'Masukkan nominal uang tunai tanpa pemisah ribuan.';
        const paid = paidCents();
        document.getElementById('paymentChange').textContent = money(Math.max(0, (paid ?? 0) - total));
        finish.disabled = sending || paid === null || paid < total;
        message.textContent = amount.value && (paid === null || paid < total) ? 'Nominal pembayaran belum mencukupi atau format tidak valid.' : '';
    }
    form.querySelectorAll('input[name="sumber_id"]').forEach(input => input.addEventListener('change', () => { update(); if (!amount.readOnly) amount.focus(); }));
    amount.addEventListener('input', update);
    form.addEventListener('submit', event => {
        if (sending) { event.preventDefault(); return; }
        update();
        if (finish.disabled) { event.preventDefault(); return; }
        sending = true; finish.disabled = true; finish.textContent = 'Menyelesaikan...';
    });
    update();
})();
