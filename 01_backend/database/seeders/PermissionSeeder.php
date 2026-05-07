<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Evaluation
            ['name' => 'evaluation.view', 'display_name' => 'Xem đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.create', 'display_name' => 'Tạo đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.edit', 'display_name' => 'Sửa đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.delete', 'display_name' => 'Xóa đánh giá', 'module' => 'evaluation'],
            ['name' => 'evaluation.approve', 'display_name' => 'Phê duyệt đánh giá', 'module' => 'evaluation'],
            
            // Report
            ['name' => 'report.view', 'display_name' => 'Xem báo cáo', 'module' => 'report'],
            ['name' => 'report.generate', 'display_name' => 'Tạo báo cáo', 'module' => 'report'],
            ['name' => 'report.export', 'display_name' => 'Xuất báo cáo', 'module' => 'report'],
            
            // User
            ['name' => 'user.view', 'display_name' => 'Xem người dùng', 'module' => 'user'],
            ['name' => 'user.create', 'display_name' => 'Tạo người dùng', 'module' => 'user'],
            ['name' => 'user.edit', 'display_name' => 'Sửa người dùng', 'module' => 'user'],
            ['name' => 'user.delete', 'display_name' => 'Xóa người dùng', 'module' => 'user'],
            
            // Role
            ['name' => 'role.view', 'display_name' => 'Xem vai trò', 'module' => 'role'],
            ['name' => 'role.create', 'display_name' => 'Tạo vai trò', 'module' => 'role'],
            ['name' => 'role.edit', 'display_name' => 'Sửa vai trò', 'module' => 'role'],
            ['name' => 'role.delete', 'display_name' => 'Xóa vai trò', 'module' => 'role'],
            
            // Scan
            ['name' => 'scan.code.view', 'display_name' => 'Xem quét code', 'module' => 'scan'],
            ['name' => 'scan.code.run', 'display_name' => 'Chạy quét code', 'module' => 'scan'],
            ['name' => 'scan.web.view', 'display_name' => 'Xem quét web', 'module' => 'scan'],
            ['name' => 'scan.web.run', 'display_name' => 'Chạy quét web', 'module' => 'scan'],
            ['name' => 'scan.db.view', 'display_name' => 'Xem quét database', 'module' => 'scan'],
            ['name' => 'scan.db.run', 'display_name' => 'Chạy quét database', 'module' => 'scan'],
            
            // Security
            ['name' => 'security.audit', 'display_name' => 'Kiểm toán bảo mật', 'module' => 'security'],
            ['name' => 'security.monitor', 'display_name' => 'Giám sát bảo mật', 'module' => 'security'],
            ['name' => 'security.manage', 'display_name' => 'Quản lý bảo mật', 'module' => 'security'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm['name']], $perm);
        }
    }
}