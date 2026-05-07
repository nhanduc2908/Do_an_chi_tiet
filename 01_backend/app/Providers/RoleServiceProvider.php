<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Role;
use App\Models\Permission;

class RoleServiceProvider extends ServiceProvider
{
    protected array $defaultRoles = [
        ['name' => 'admin', 'display_name' => 'Quản trị viên', 'level' => 100, 'color' => '#FF0000', 'icon' => 'shield-crown', 'requires_key' => true, 'requires_otp' => true, 'max_sessions' => 3],
        ['name' => 'dev', 'display_name' => 'Lập trình viên', 'level' => 30, 'color' => '#00FF00', 'icon' => 'code-braces', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 5],
        ['name' => 'ba', 'display_name' => 'Phân tích nghiệp vụ', 'level' => 35, 'color' => '#00CCFF', 'icon' => 'chart-line', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 4],
        ['name' => 'da', 'display_name' => 'Chuyên gia dữ liệu', 'level' => 35, 'color' => '#9900FF', 'icon' => 'database', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 4],
        ['name' => 'hr', 'display_name' => 'Nhân sự', 'level' => 20, 'color' => '#FF9900', 'icon' => 'users', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 3],
        ['name' => 'qa', 'display_name' => 'Kiểm thử', 'level' => 40, 'color' => '#FF00FF', 'icon' => 'bug', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 4],
        ['name' => 'secops', 'display_name' => 'Vận hành bảo mật', 'level' => 50, 'color' => '#00FFFF', 'icon' => 'shield', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 3],
        ['name' => 'auditor', 'display_name' => 'Kiểm toán', 'level' => 60, 'color' => '#FFFF00', 'icon' => 'clipboard', 'requires_key' => false, 'requires_otp' => false, 'max_sessions' => 3],
        ['name' => 'manager', 'display_name' => 'Quản lý', 'level' => 70, 'color' => '#FF6600', 'icon' => 'briefcase', 'requires_key' => false, 'requires_otp' => true, 'max_sessions' => 3],
        ['name' => 'ciso', 'display_name' => 'Giám đốc ATTT', 'level' => 90, 'color' => '#FF0066', 'icon' => 'crown', 'requires_key' => true, 'requires_otp' => true, 'max_sessions' => 2],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tạo 10 role mặc định nếu chưa có
        foreach ($this->defaultRoles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Tạo permissions mặc định
        $this->createDefaultPermissions();
    }

    protected function createDefaultPermissions(): void
    {
        $permissions = [
            // Evaluation permissions
            ['name' => 'evaluation.view', 'display_name' => 'Xem đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.create', 'display_name' => 'Tạo đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.edit', 'display_name' => 'Sửa đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.delete', 'display_name' => 'Xóa đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.approve', 'display_name' => 'Phê duyệt đánh giá', 'module' => 'evaluation'],
            
            // Report permissions
            ['name' => 'report.view', 'display_name' => 'Xem báo cáo', 'module' => 'report'],
            ['name' => 'report.generate', 'display_name' => 'Tạo báo cáo', 'module' => 'report'],
            ['name' => 'report.export', 'display_name' => 'Xuất báo cáo', 'module' => 'report'],
            
            // User permissions
            ['name' => 'user.view', 'display_name' => 'Xem người dùng', 'module' => 'user'],
            ['name' => 'user.create', 'display_name' => 'Tạo người dùng', 'module' => 'user'],
            ['name' => 'user.edit', 'display_name' => 'Sửa người dùng', 'module' => 'user'],
            ['name' => 'user.delete', 'display_name' => 'Xóa người dùng', 'module' => 'user'],
            
            // Role permissions
            ['name' => 'role.view', 'display_name' => 'Xem vai trò', 'module' => 'role'],
            ['name' => 'role.create', 'display_name' => 'Tạo vai trò', 'module' => 'role'],
            ['name' => 'role.edit', 'display_name' => 'Sửa vai trò', 'module' => 'role'],
            ['name' => 'role.delete', 'display_name' => 'Xóa vai trò', 'module' => 'role'],
            
            // Scan permissions
            ['name' => 'scan.code.view', 'display_name' => 'Xem quét code', 'module' => 'scan'],
            ['name' => 'scan.code.run', 'display_name' => 'Chạy quét code', 'module' => 'scan'],
            ['name' => 'scan.web.view', 'display_name' => 'Xem quét web', 'module' => 'scan'],
            ['name' => 'scan.web.run', 'display_name' => 'Chạy quét web', 'module' => 'scan'],
            
            // Security permissions
            ['name' => 'security.audit', 'display_name' => 'Kiểm toán bảo mật', 'module' => 'security'],
            ['name' => 'security.monitor', 'display_name' => 'Giám sát bảo mật', 'module' => 'security'],
            ['name' => 'security.manage', 'display_name' => 'Quản lý bảo mật', 'module' => 'security'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                $perm
            );
        }
    }
}