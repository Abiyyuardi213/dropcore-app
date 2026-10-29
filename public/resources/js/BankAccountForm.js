(() => {
    'use strict';
    const bank = document.getElementById('bank_select');
    if (!bank) return;
    const type = document.querySelector('select[name="jenis"]');
    const realName = document.getElementById('real_nama_sumber');
    const manualName = document.getElementById('manual_nama_sumber');
    const bankContainer = document.getElementById('bank_select_container');
    const manualContainer = document.getElementById('manual_input_container');
    if (type.value === 'bank' && realName.value && !Array.from(bank.options).some(option => option.value === realName.value)) bank.value = '__manual__';
    function sync() {
        const isBank = type.value === 'bank';
        const isManual = !isBank || bank.value === '__manual__';
        bankContainer.style.display = isBank ? '' : 'none';
        manualContainer.style.display = isManual ? '' : 'none';
        bank.disabled = !isBank;
        bank.required = isBank;
        manualName.disabled = !isManual;
        manualName.required = isManual;
        manualName.maxLength = 255;
        manualName.placeholder = isBank ? 'Masukkan nama bank / BPR' : 'Contoh: Kas Kecil, Dana, OVO';
        realName.value = isManual ? manualName.value.trim() : bank.value;
    }
    type.addEventListener('change', sync);
    bank.addEventListener('change', sync);
    manualName.addEventListener('input', sync);
    bank.closest('form').addEventListener('submit', sync);
    if (window.jQuery?.fn.select2) {
        window.jQuery('.select2bs4').select2({theme: 'bootstrap4'});
        window.jQuery(type).on('change', sync);
        window.jQuery(bank).on('change', sync);
    }
    sync();
})();
