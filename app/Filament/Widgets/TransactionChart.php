<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Filament\Support\RawJs;

class TransactionChart extends ChartWidget
{
    protected ?string $heading = 'Transaction Overview';
    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $filter = $this->filter ?? 'daily';

        $now = Carbon::now();
        $labels = [];
        $data = [];

        $dateColumn = Schema::hasColumn('transactions', 'transaction_date') ? 'transaction_date' : 'created_at';
        $hasAmount = Schema::hasColumn('transactions', 'amount_paid');
        $sumExpr = $hasAmount ? 'SUM(amount_paid)' : 'COUNT(*)';
        $datasetLabel = $hasAmount ? 'Total Pembayaran' : 'Jumlah Transaksi';

        switch ($filter) {
            case 'weekly':
                $end = $now->copy()->endOfWeek();
                $start = $now->copy()->subWeeks(11)->startOfWeek();

                $rows = Transaction::query()
                    ->selectRaw("YEARWEEK({$dateColumn}, 3) as period, {$sumExpr} as total")
                    ->whereBetween($dateColumn, [$start, $end])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->pluck('total', 'period')
                    ->all();

                $cursor = $start->copy();
                while ($cursor->lte($end)) {
                    $key = (string) ($cursor->isoWeekYear . str_pad((string) $cursor->isoWeek, 2, '0', STR_PAD_LEFT));
                    $labels[] = $cursor->copy()->startOfWeek()->format('d M');
                    $data[] = (float) ($rows[$key] ?? 0);
                    $cursor->addWeek();
                }
                break;

            case 'monthly':
                $end = $now->copy()->endOfMonth();
                $start = $now->copy()->subMonthsNoOverflow(11)->startOfMonth();

                $rows = Transaction::query()
                    ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as period, {$sumExpr} as total")
                    ->whereBetween($dateColumn, [$start, $end])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->pluck('total', 'period')
                    ->all();

                $cursor = $start->copy();
                while ($cursor->lte($end)) {
                    $key = $cursor->format('Y-m');
                    $labels[] = $cursor->format('M Y');
                    $data[] = (float) ($rows[$key] ?? 0);
                    $cursor->addMonthNoOverflow();
                }
                break;

            case 'daily':
            default:
                $end = $now->copy()->endOfDay();
                $start = $now->copy()->subDays(29)->startOfDay();

                $rows = Transaction::query()
                    ->selectRaw("DATE({$dateColumn}) as period, {$sumExpr} as total")
                    ->whereBetween($dateColumn, [$start, $end])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->pluck('total', 'period')
                    ->all();

                $cursor = $start->copy();
                while ($cursor->lte($end)) {
                    $key = $cursor->toDateString();
                    $labels[] = $cursor->format('d M');
                    $data[] = (float) ($rows[$key] ?? 0);
                    $cursor->addDay();
                }
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $datasetLabel,
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ];
    }

    protected function getOptions(): array | RawJs | null
    {
        $hasAmount = Schema::hasColumn('transactions', 'amount_paid');

        if ($hasAmount) {
            return RawJs::make(<<<'JS'
                ({
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.y ?? 0;
                                    const label = (context.dataset && context.dataset.label) ? context.dataset.label + ': ' : '';
                                    const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val).replace('IDR', 'Rp');
                                    return label + formatted;
                                },
                            },
                        },
                    },
                    elements: {
                        line: { borderJoinStyle: 'round', capBezierPoints: true },
                        point: { hoverRadius: 4 },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 12 },
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.06)', drawBorder: false },
                            ticks: {
                                precision: 0,
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value ?? 0);
                                },
                            },
                        },
                    },
                })
            JS);
        }

        return RawJs::make(<<<'JS'
            ({
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed.y ?? 0;
                                const label = (context.dataset && context.dataset.label) ? context.dataset.label + ': ' : '';
                                return label + (new Intl.NumberFormat('id-ID').format(val));
                            },
                        },
                    },
                },
                elements: {
                    line: { borderJoinStyle: 'round', capBezierPoints: true },
                    point: { hoverRadius: 4 },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 12 },
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.06)', drawBorder: false },
                        ticks: {
                            precision: 0,
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value ?? 0);
                            },
                        },
                    },
                },
            })
        JS);
    }
}
