<?php

namespace Tests\Feature;

use App\Services\DashboardSalesService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};
use Tests\TestCase;

class DashboardSalesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('orders', function (Blueprint $t) { $t->string('id')->primary(); $t->string('order_number'); $t->string('status'); $t->decimal('total_price', 15, 2); $t->timestamp('created_at'); });
        Schema::create('pengeluaran_barang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('status'); $t->string('referensi')->nullable(); $t->date('tanggal_pengeluaran'); });
        Schema::create('pengeluaran_barang_detail', function (Blueprint $t) { $t->increments('id'); $t->string('pengeluaran_id'); $t->decimal('subtotal', 15, 2); });
    }

    private function outgoing(string $id, string $status, string $date, array $amounts, ?string $reference = null): void
    {
        DB::table('pengeluaran_barang')->insert(['id' => $id, 'status' => $status, 'tanggal_pengeluaran' => $date, 'referensi' => $reference]);
        foreach ($amounts as $amount) DB::table('pengeluaran_barang_detail')->insert(['pengeluaran_id' => $id, 'subtotal' => $amount]);
    }

    public function test_completed_outgoing_values_and_counts_are_included_but_not_draft_or_canceled(): void
    {
        $this->outgoing('sale', 'completed', '2026-10-25', [100, 250.50]);
        $this->outgoing('draft', 'pending', '2026-10-25', [900]);
        $this->outgoing('canceled', 'canceled', '2026-10-25', [800]);
        $data = app(DashboardSalesService::class)->trend(CarbonImmutable::parse('2026-10-31'));
        $this->assertEquals(350.50, $data['salesChartData'][5]);
        $this->assertEquals(350.50, $data['totalPenjualanBulanIni']);
        $this->assertEquals(1, $data['pengeluaranChartData'][5]);
        $this->assertCount(6, array_unique($data['salesChartLabels']));
        $this->assertEquals([0, 0, 0, 0, 0], array_slice($data['salesChartData'], 0, 5));
    }

    public function test_order_generated_outgoing_is_not_double_counted_even_across_months(): void
    {
        DB::table('orders')->insert([
            ['id' => 'o1', 'order_number' => 'ORD-1', 'status' => 'completed', 'total_price' => 600, 'created_at' => '2026-09-30 12:00:00'],
            ['id' => 'o2', 'order_number' => 'ORD-2', 'status' => 'processing', 'total_price' => 300, 'created_at' => '2026-10-01 12:00:00'],
            ['id' => 'o3', 'order_number' => 'ORD-3', 'status' => 'cancelled', 'total_price' => 999, 'created_at' => '2026-10-01 12:00:00'],
        ]);
        $this->outgoing('linked', 'completed', '2026-10-01', [600], 'ORD-1');
        $this->outgoing('direct', 'completed', '2026-10-25', [200]);
        $data = app(DashboardSalesService::class)->trend(CarbonImmutable::parse('2026-10-31'));
        $this->assertEquals(600, $data['salesChartData'][4]);
        $this->assertEquals(500, $data['salesChartData'][5]);
        $this->assertEquals(2, $data['pengeluaranChartData'][5]);
    }

    public function test_six_month_window_crosses_year_and_fills_empty_months(): void
    {
        $this->outgoing('old', 'completed', '2025-07-31', [999]);
        $this->outgoing('first', 'completed', '2025-08-01', [100]);
        $this->outgoing('last', 'completed', '2026-01-31', [250]);
        $this->outgoing('future', 'completed', '2026-02-01', [999]);
        $data = app(DashboardSalesService::class)->trend(CarbonImmutable::parse('2026-01-31'));
        $this->assertEquals([100, 0, 0, 0, 0, 250], $data['salesChartData']);
        $this->assertEquals([1, 0, 0, 0, 0, 1], $data['pengeluaranChartData']);
        $this->assertEquals(250, $data['totalPenjualanBulanIni']);
    }
}
