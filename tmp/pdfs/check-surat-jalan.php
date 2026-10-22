<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$document = App\Models\PengeluaranBarang::with(['distributor', 'user', 'details.produk', 'details.gudang', 'details.area', 'details.rak', 'details.kondisi'])->findOrFail('ec9f16ed-eb5e-493f-90db-f3fa94f70020');
Barryvdh\DomPDF\Facade\Pdf::loadView('pengeluaran_barang.pdf', ['pengeluaran' => $document])->setPaper('a4')->save('tmp/pdfs/surat-jalan-preview.pdf');
echo "PDF generated\n";
