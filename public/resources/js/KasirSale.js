(() => {
    'use strict';
    const cfg = window.cashierSaleConfig;
    const el = id => document.getElementById(id);
    const form = el('saleForm'); if (!form || !cfg) return;
    const cart = new Map(); let products = []; let sending = false; let restoring = false; let requestId = 0;
    let searchTimer; let searchController;
    const money = cents => 'Rp ' + (cents / 100).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const cents = value => Math.round((Number(value) || 0) * 100);
    const message = text => { el('saleMessage').textContent = text; el('saleMessage').hidden = !text; };
    const selectedProduct = () => products.find(p => p.id === el('productSelect').value);
    const selectedStock = () => selectedProduct()?.stocks.find(s => s.id === el('stockSelect').value);
    const textCell = (row, text, className = '') => { const td = document.createElement('td'); td.textContent = text; td.className = className; row.append(td); return td; };
    function preview() {
        const product = selectedProduct(), stock = selectedStock();
        el('productPrice').value = product ? money(cents(product.price)) : '';
        el('stockHint').textContent = stock ? 'Tersedia: ' + stock.quantity + ' ' + selectedProduct().unit : '';
        el('addItem').disabled = !stock;
    }
    function selectProduct() {
        const product = selectedProduct();
        el('stockSelect').replaceChildren(new Option('Pilih lokasi stok', ''));
        product?.stocks.forEach(stock => el('stockSelect').append(new Option(stock.label + ' (Stok: ' + stock.quantity + ')', stock.id)));
        el('stockSelect').disabled = !product;
        if (product?.stocks.length === 1) el('stockSelect').value = product.stocks[0].id;
        preview();
    }
    async function loadProducts() {
        clearTimeout(searchTimer); searchController?.abort();
        searchController = new AbortController();
        const current = ++requestId; el('findProduct').disabled = true;
        el('productSearchStatus').textContent = 'Mencari produk...';
        try {
            const url = new URL(cfg.productsUrl); url.searchParams.set('search', el('productCode').value.trim());
            const response = await fetch(url, {headers: {'Accept':'application/json'}, signal: searchController.signal});
            if (!response.ok) throw new Error('Pencarian produk gagal. Muat ulang halaman bila sesi login berakhir.');
            const data = await response.json(); if (current !== requestId) return;
            products = data; el('productSelect').replaceChildren(new Option(data.length ? 'Pilih produk' : 'Produk dengan stok tersedia tidak ditemukan', ''));
            data.forEach(p => el('productSelect').append(new Option((p.sku || '-') + ' - ' + p.name, p.id)));
            if (data.length === 1) el('productSelect').value = data[0].id;
            selectProduct(); el('productSearchStatus').textContent = data.length ? data.length + ' produk ditemukan. Pilih produk untuk melanjutkan.' : 'Tidak ditemukan produk dengan stok tersedia.';
            return true;
        } catch (error) { if (current === requestId && error.name !== 'AbortError') { message(error.message); el('productSearchStatus').textContent = 'Pencarian gagal. Silakan coba lagi.'; } }
        finally { if (current === requestId) el('findProduct').disabled = false; }
    }
    function totals() {
        let gross = 0, net = 0, quantity = 0;
        const discount = Math.max(0, Math.min(100, Number(el('discount').value) || 0));
        cart.forEach(line => { const price = cents(line.product.price); gross += price * line.quantity; net += Math.round(price * (100 - discount) / 100) * line.quantity; quantity += line.quantity; });
        el('cartQuantity').textContent = quantity; el('cartGross').textContent = money(gross);
        el('subtotal').textContent = money(gross); el('discountAmount').textContent = money(gross - net); el('grandTotal').textContent = money(net);
        el('saveSale').disabled = !cart.size || sending || restoring || !cfg.hasAccounts;
        return net;
    }
    function renderCart() {
        el('cartBody').replaceChildren(); el('cartInputs').replaceChildren();
        if (!cart.size) { const row = document.createElement('tr'); const cell = textCell(row, 'Belum ada barang. Cari produk lalu tambahkan ke daftar belanja.', 'text-center text-muted py-4'); cell.colSpan = 6; el('cartBody').append(row); }
        let index = 0;
        cart.forEach((line, id) => {
            const row = document.createElement('tr'); const info = textCell(row, line.product.sku || '-');
            const location = document.createElement('small'); location.className = 'text-muted d-block'; location.textContent = line.stock.label; info.append(location);
            textCell(row, line.product.name); textCell(row, money(cents(line.product.price)), 'text-right text-nowrap');
            const qtyCell = textCell(row, ''); const qty = document.createElement('input'); qty.type = 'number'; qty.className = 'form-control form-control-sm'; qty.min = '1'; qty.max = String(Math.min(line.stock.quantity, 100000)); qty.step = '1'; qty.value = line.quantity; qty.setAttribute('aria-label', 'Jumlah ' + line.product.name);
            qtyCell.append(qty);
            const lineTotal = textCell(row, money(cents(line.product.price) * line.quantity), 'text-right text-nowrap');
            const action = textCell(row, ''); const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'btn btn-danger btn-xs'; remove.textContent = 'Batal'; remove.addEventListener('click', () => { cart.delete(id); renderCart(); }); action.append(remove); el('cartBody').append(row);
            let quantityInput;
            for (const [key,value] of Object.entries({stock_id: id, quantity: line.quantity})) { const input=document.createElement('input'); input.type='hidden'; input.name=`items[${index}][${key}]`; input.value=value; el('cartInputs').append(input); if (key === 'quantity') quantityInput = input; }
            qty.addEventListener('input', () => {
                const n = Number(qty.value);
                if (!Number.isInteger(n) || n < 1 || n > Number(qty.max)) { qty.setCustomValidity('Jumlah harus antara 1 dan stok tersedia.'); return; }
                qty.setCustomValidity(''); line.quantity = n; quantityInput.value = n;
                lineTotal.textContent = money(cents(line.product.price) * n); totals();
            });
            qty.addEventListener('change', () => { if (!qty.checkValidity()) { message('Jumlah barang harus antara 1 dan stok tersedia.'); qty.value = line.quantity; qty.setCustomValidity(''); } });
            qty.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); qty.blur(); el('productCode').focus(); } });
            index++;
        });
        totals(); preview();
    }
    el('findProduct').addEventListener('click', loadProducts);
    el('productCode').addEventListener('input', () => {
        clearTimeout(searchTimer); searchController?.abort(); ++requestId;
        products = []; el('productSelect').replaceChildren(new Option('Menunggu hasil pencarian...', '')); selectProduct();
        el('findProduct').disabled = false;
        el('productSearchStatus').textContent = 'Mencari produk...';
        searchTimer = setTimeout(loadProducts, 300);
    });
    let addingFromSearch = false;
    el('productCode').addEventListener('keydown', async e => {
        if (e.key !== 'Enter' || e.isComposing) return;
        e.preventDefault(); if (e.repeat || addingFromSearch) return;
        addingFromSearch = true;
        try {
            if (!selectedProduct() && !await loadProducts()) return;
            addSelectedItem();
        } finally { addingFromSearch = false; }
    });
    el('productSelect').addEventListener('change', selectProduct); el('stockSelect').addEventListener('change', preview);
    for (const id of ['productSelect', 'stockSelect']) el(id).addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); if (!e.repeat) addSelectedItem(); } });
    function addSelectedItem() {
        const product=selectedProduct(), stock=selectedStock(), qty=1;
        if (!product) { message('Pilih salah satu produk dari hasil pencarian.'); el('productSelect').focus(); return; }
        if (!stock) { message('Pilih lokasi stok produk terlebih dahulu.'); el('stockSelect').focus(); return; }
        if (!stock || !Number.isInteger(qty) || qty < 1 || qty + (cart.get(stock.id)?.quantity || 0) > Math.min(stock.quantity,100000)) { message('Pilih produk/lokasi dan jumlah sesuai stok tersedia.'); return; }
        if (!cart.has(stock.id) && cart.size >= 100) { message('Maksimal 100 lokasi stok dalam satu transaksi.'); return; }
        if (cart.has(stock.id)) cart.get(stock.id).quantity += qty;
        else cart.set(stock.id,{product,stock,quantity:qty});
        message(''); renderCart(); el('productCode').focus(); el('productCode').select();
    }
    el('addItem').addEventListener('click', addSelectedItem);
    el('discount').addEventListener('input', totals);
    el('resetSale').addEventListener('click', () => { if (cart.size && !window.confirm('Kosongkan transaksi yang belum disimpan?')) return; cart.clear(); form.reset(); message(''); renderCart(); });
    form.addEventListener('submit', e => {
        if (sending || restoring || !cart.size) {e.preventDefault(); message('Tambahkan barang sebelum menyimpan transaksi.'); return;}
        sending=true; el('saveSale').disabled=true; el('saveSale').textContent='Membuka Proses Bayar...';
    });
    async function restore() {
        if (!cfg.oldItems?.length) return;
        restoring=true; totals();
        try {
            for (const old of cfg.oldItems) {
                const url=new URL(cfg.productsUrl); url.searchParams.set('stock_id',old.stock_id);
                const response=await fetch(url,{headers:{'Accept':'application/json'}}); if (!response.ok) throw new Error('Keranjang gagal dipulihkan. Cari ulang produk.');
                const found=await response.json(); const product=found.find(p=>p.stocks.some(s=>s.id===old.stock_id)); const stock=product?.stocks.find(s=>s.id===old.stock_id);
                if (stock) cart.set(stock.id,{product,stock,quantity:Math.min(Number(old.quantity)||1,stock.quantity,100000)});
                else message('Sebagian stok sudah habis. Periksa kembali keranjang dan pembayaran.');
            }
        } catch(error) {message(error.message);}
        finally {restoring=false; renderCart();}
    }
    renderCart(); restore();
})();
