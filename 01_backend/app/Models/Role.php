<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // 10 ROLE CONSTANTS
    public const ADMIN = 'admin';
    public const DEV = 'dev';
    public const BA = 'ba';
    public const DA = 'da';
    public const HR = 'hr';
    public const QA = 'qa';
    public const SECOPS = 'secops';
    public const AUDITOR = 'auditor';
    public const MANAGER = 'manager';
    public const CISO = 'ciso';

    protected $fillable = [
        'name', 'display_name', 'description', 'level', 'color', 'icon',
        'requires_key', 'requires_otp', 'max_sessions',
    ];

    protected $casts = [
        'requires_key' => 'boolean',
        'requires_otp' => 'boolean',
        'level' => 'integer',
        'max_sessions' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role', 'name');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public static function getRolesList(): array
    {
        return [
            self::ADMIN => ['name' => 'Quản trị viên', 'level' => 100, 'color' => '#FF0000', 'requires_key' => true, 'requires_otp' => true],
            self::DEV => ['name' => 'Lập trình viên', 'level' => 30, 'color' => '#00FF00', 'requires_key' => false, 'requires_otp' => false],
            self::BA => ['name' => 'Phân tích nghiệp vụ', 'level' => 35, 'color' => '#00CCFF', 'requires_key' => false, 'requires_otp' => false],
            self::DA => ['name' => 'Chuyên gia dữ liệu', 'level' => 35, 'color' => '#9900FF', 'requires_key' => false, 'requires_otp' => false],
            self::HR => ['name' => 'Nhân sự', 'level' => 20, 'color' => '#FF9900', 'requires_key' => false, 'requires_otp' => false],
            self::QA => ['name' => 'Kiểm thử', 'level' => 40, 'color' => '#FF00FF', 'requires_key' => false, 'requires_otp' => false],
            self::SECOPS => ['name' => 'Vận hành bảo mật', 'level' => 50, 'color' => '#00FFFF', 'requires_key' => false, 'requires_otp' => false],
            self::AUDITOR => ['name' => 'Kiểm toán', 'level' => 60, 'color' => '#FFFF00', 'requires_key' => false, 'requires_otp' => false],
            self::MANAGER => ['name' => 'Quản lý', 'level' => 70, 'color' => '#FF6600', 'requires_key' => false, 'requires_otp' => true],
            self::CISO => ['name' => 'Giám đốc ATTT', 'level' => 90, 'color' => '#FF0066', 'requires_key' => true, 'requires_otp' => true],
        ];
    }

    public static function getPermissions(string $role): array
    {
        $permissions = [
            self::ADMIN => ['*'],
            self::DEV => ['evaluation.view', 'evaluation.create', 'scan.code.view', 'scan.code.run', 'vulnerability.view'],
            self::BA => ['evaluation.view', 'evaluation.create', 'process.view', 'risk.view'],
            self::DA => ['evaluation.view', 'database.scan', 'data.analyze'],
            self::HR => ['evaluation.view', 'policy.view', 'training.view'],
            self::QA => ['evaluation.view', 'pentest.view', 'test.view'],
            self::SECOPS => ['evaluation.view', 'monitoring.view', 'incident.view'],
            self::AUDITOR => ['evaluation.view', 'audit.log.view', 'compliance.view'],
            self::MANAGER => ['evaluation.view', 'evaluation.approve', 'team.view', 'report.view'],
            self::CISO => ['evaluation.view', 'strategy.view', 'budget.view', 'board.report'],
        ];
        return $permissions[$role] ?? [];
    }
}