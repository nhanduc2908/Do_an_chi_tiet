<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskAssessment;
use App\Models\Asset;
use App\Models\User;

class RiskSeeder extends Seeder
{
    public function run(): void
    {
        $assets = Asset::all();
        $users = User::all();

        $risks = [
            ['threat' => 'Mất dữ liệu', 'vulnerability' => 'Sao lưu không đầy đủ', 'likelihood' => 3, 'impact' => 5],
            ['threat' => 'Tấn công ransomware', 'vulnerability' => 'Hệ thống chưa được vá', 'likelihood' => 2, 'impact' => 5],
            ['threat' => 'Lộ thông tin', 'vulnerability' => 'Kiểm soát truy cập yếu', 'likelihood' => 4, 'impact' => 4],
            ['threat' => 'Gián đoạn dịch vụ', 'vulnerability' => 'Hạ tầng đơn điểm', 'likelihood' => 3, 'impact' => 4],
            ['threat' => 'Tấn công DDoS', 'vulnerability' => 'Thiếu bảo vệ', 'likelihood' => 2, 'impact' => 3],
        ];

        foreach ($assets as $asset) {
            foreach ($risks as $risk) {
                $riskScore = $risk['likelihood'] * $risk['impact'];
                $riskLevel = $this->getRiskLevel($riskScore);
                
                RiskAssessment::create([
                    'asset_id' => $asset->id,
                    'threat' => $risk['threat'],
                    'vulnerability' => $risk['vulnerability'],
                    'likelihood' => $risk['likelihood'],
                    'impact' => $risk['impact'],
                    'risk_score' => $riskScore,
                    'risk_level' => $riskLevel,
                    'mitigation' => 'Cần xây dựng kế hoạch khắc phục',
                    'owner_id' => $users->random()->id,
                    'assessed_by' => $users->random()->id,
                    'assessed_at' => now(),
                ]);
            }
        }
    }

    private function getRiskLevel($score)
    {
        if ($score >= 20) return 'critical';
        if ($score >= 12) return 'high';
        if ($score >= 6) return 'medium';
        return 'low';
    }
}