(() => {
    'use strict';
    const container = document.getElementById('warehouseCards');
    if (!container) return;
    const cards = Array.from(container.querySelectorAll('.warehouse-card'));
    const search = document.getElementById('warehouseCardSearch');
    const status = document.getElementById('warehouseCardStatus');
    const warehouse = document.getElementById('areaWarehouseFilter');
    const area = document.getElementById('rackAreaFilter');
    const capacity = document.getElementById('rackCapacityFilter');
    const entity = container.dataset.entity || 'gudang';
    function filter() {
        const term = search.value.trim().toLocaleLowerCase('id-ID');
        let visible = 0;
        cards.forEach(card => { card.hidden = !card.dataset.search.includes(term) || (!!status.value && card.dataset.active !== status.value) || (!!warehouse?.value && card.dataset.warehouseId !== warehouse.value) || (!!area?.value && card.dataset.areaId !== area.value) || (!!capacity?.value && card.dataset.capacityStatus !== capacity.value); if (!card.hidden) visible++; });
        document.getElementById('warehouseCardCount').textContent = 'Menampilkan ' + visible + ' dari ' + cards.length + ' ' + entity;
        document.getElementById('warehouseCardsEmpty').hidden = visible > 0;
    }
    search.addEventListener('input', filter); status.addEventListener('change', filter);
    warehouse?.addEventListener('change', () => {
        if (area) {
            Array.from(area.options).forEach(option => { option.hidden = !!option.value && !!warehouse.value && option.dataset.warehouseId !== warehouse.value; });
            if (area.selectedOptions[0]?.hidden) area.value = '';
        }
        filter();
    });
    area?.addEventListener('change', filter);
    capacity?.addEventListener('change', filter);
    container.querySelectorAll('.delete-gudang-btn').forEach(button => button.addEventListener('click', async () => {
        const result = await Swal.fire({title:'Hapus ' + entity + '?', text:'Data ' + entity + ' yang dihapus tidak dapat dikembalikan.', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal'});
        if (result.isConfirmed) { const form = document.getElementById('deleteWarehouseForm'); form.action = button.dataset.url; form.submit(); }
    }));
    container.querySelectorAll('.warehouse-status-toggle').forEach(toggle => toggle.addEventListener('change', async () => {
        const previous = !toggle.checked; toggle.disabled = true;
        try {
            const response = await fetch(toggle.dataset.url, {method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}});
            if (!response.ok) throw new Error('Status ' + entity + ' gagal diperbarui. Silakan coba lagi.');
            const data = await response.json(); if (!data.success) throw new Error(data.message || 'Gagal memperbarui status ' + entity + '.');
            toggle.checked = !!data.active;
            const card = toggle.closest('.warehouse-card'); card.dataset.active = data.active ? '1' : '0';
            const badge = card.querySelector('.warehouse-status-badge'); badge.textContent = data.active ? 'Aktif' : 'Nonaktif'; badge.className = 'badge warehouse-status-badge badge-' + (data.active ? 'success' : 'secondary');
            filter(); Swal.fire({toast:true,position:'top-end',icon:'success',title:data.message,showConfirmButton:false,timer:3000});
        } catch(error) { toggle.checked = previous; Swal.fire({icon:'error',title:'Gagal',text:error.message}); }
        finally { toggle.disabled = false; }
    }));
})();
