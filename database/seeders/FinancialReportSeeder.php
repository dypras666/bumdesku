<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FinancialReport;
use App\Models\User;
use Carbon\Carbon;

class FinancialReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user admin untuk membuat laporan
        $adminUser = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        if (!$adminUser) {
            $adminUser = User::first(); // Fallback ke user pertama jika tidak ada admin
            if (!$adminUser) {
                $this->command->error('No users found. Please run UserSeeder first.');
                return;
            }
        }
        
        $reportCounter = 1;
        $reports = [
            // Laporan Bulanan - 3 bulan terakhir
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'income_statement',
                'report_title' => 'Laporan Laba Rugi - ' . Carbon::now()->subMonths(3)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(3)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'updated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'balance_sheet',
                'report_title' => 'Neraca - ' . Carbon::now()->subMonths(3)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(3)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'updated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'cash_flow',
                'report_title' => 'Laporan Arus Kas - ' . Carbon::now()->subMonths(3)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(3)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
                'updated_at' => Carbon::now()->subMonths(3)->endOfMonth()->addDays(2),
            ],
            
            // Laporan Bulan ke-2
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'income_statement',
                'report_title' => 'Laporan Laba Rugi - ' . Carbon::now()->subMonths(2)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(2)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(2)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'updated_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'balance_sheet',
                'report_title' => 'Neraca - ' . Carbon::now()->subMonths(2)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(2)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(2)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
                'updated_at' => Carbon::now()->subMonths(2)->endOfMonth()->addDays(1),
            ],
            
            // Laporan Bulan lalu
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'income_statement',
                'report_title' => 'Laporan Laba Rugi - ' . Carbon::now()->subMonths(1)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(1)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'balance_sheet',
                'report_title' => 'Neraca - ' . Carbon::now()->subMonths(1)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(1)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'cash_flow',
                'report_title' => 'Laporan Arus Kas - ' . Carbon::now()->subMonths(1)->format('F Y'),
                'period_start' => Carbon::now()->subMonths(1)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(1),
            ],
            
            // Laporan Bulan ini (draft dan generated)
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'income_statement',
                'report_title' => 'Laporan Laba Rugi - ' . Carbon::now()->format('F Y'),
                'period_start' => Carbon::now()->startOfMonth(),
                'period_end' => Carbon::now()->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subDays(5),
                'status' => 'draft',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'balance_sheet',
                'report_title' => 'Neraca - ' . Carbon::now()->format('F Y'),
                'period_start' => Carbon::now()->startOfMonth(),
                'period_end' => Carbon::now()->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subDays(3),
                'status' => 'generated',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            
            // Laporan Triwulan
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'income_statement',
                'report_title' => 'Laporan Laba Rugi Triwulan - Q' . Carbon::now()->quarter . ' ' . Carbon::now()->year,
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'balance_sheet',
                'report_title' => 'Neraca Triwulan - Q' . Carbon::now()->quarter . ' ' . Carbon::now()->year,
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
            ],
            [
                'report_code' => 'RPT' . str_pad($reportCounter++, 6, '0', STR_PAD_LEFT),
                'report_type' => 'cash_flow',
                'report_title' => 'Laporan Arus Kas Triwulan - Q' . Carbon::now()->quarter . ' ' . Carbon::now()->year,
                'period_start' => Carbon::now()->subMonths(3)->startOfMonth(),
                'period_end' => Carbon::now()->subMonths(1)->endOfMonth(),
                'generated_by' => $adminUser->id,
                'generated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'finalized_by' => $adminUser->id,
                'finalized_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'status' => 'finalized',
                'created_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
                'updated_at' => Carbon::now()->subMonths(1)->endOfMonth()->addDays(5),
            ],
        ];
        
        foreach ($reports as $reportData) {
            FinancialReport::create($reportData);
        }
        
        $this->command->info('Financial reports created successfully!');
    }
}
