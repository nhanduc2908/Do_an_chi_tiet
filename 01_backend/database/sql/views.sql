-- =====================================================
-- VIEWS - CÁC VIEW HỖ TRỢ BÁO CÁO VÀ THỐNG KÊ
-- =====================================================

-- 1. View tổng hợp đánh giá theo công ty
CREATE OR REPLACE VIEW v_company_evaluation_summary AS
SELECT 
    c.id AS company_id,
    c.name AS company_name,
    c.industry,
    COUNT(e.id) AS total_evaluations,
    ROUND(AVG(e.percentage), 2) AS avg_score,
    MAX(e.percentage) AS max_score,
    MIN(e.percentage) AS min_score,
    SUM(CASE WHEN e.status = 'approved' THEN 1 ELSE 0 END) AS approved_count,
    SUM(CASE WHEN e.status = 'submitted' THEN 1 ELSE 0 END) AS pending_count,
    SUM(CASE WHEN e.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count
FROM companies c
LEFT JOIN evaluations e ON e.company_id = c.id
GROUP BY c.id, c.name, c.industry;


-- 2. View chi tiết đánh giá theo domain
CREATE OR REPLACE VIEW v_domain_evaluation_detail AS
SELECT 
    d.id AS domain_id,
    d.name AS domain_name,
    e.id AS evaluation_id,
    e.title AS evaluation_title,
    u.name AS evaluator_name,
    e.percentage AS score,
    e.status,
    e.created_at,
    e.approved_at
FROM domains d
JOIN evaluations e ON e.domain_id = d.id
JOIN users u ON u.id = e.user_id;


-- 3. View thống kê theo role
CREATE OR REPLACE VIEW v_role_statistics AS
SELECT 
    u.role,
    COUNT(DISTINCT u.id) AS total_users,
    COUNT(DISTINCT e.id) AS total_evaluations,
    ROUND(AVG(e.percentage), 2) AS avg_score,
    SUM(CASE WHEN e.status = 'approved' THEN 1 ELSE 0 END) AS approved_count
FROM users u
LEFT JOIN evaluations e ON e.user_id = u.id
GROUP BY u.role;


-- 4. View lịch sử đăng nhập gần đây
CREATE OR REPLACE VIEW v_recent_logins AS
SELECT 
    lh.id,
    u.name AS user_name,
    u.email,
    lh.ip_address,
    lh.device_name,
    lh.login_at,
    lh.status,
    TIMESTAMPDIFF(HOUR, lh.login_at, NOW()) AS hours_ago
FROM login_histories lh
JOIN users u ON u.id = lh.user_id
WHERE lh.login_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY lh.login_at DESC;


-- 5. View thống kê lỗ hổng bảo mật
CREATE OR REPLACE VIEW v_vulnerability_summary AS
SELECT 
    v.scan_result_id,
    s.type AS scan_type,
    s.target,
    v.severity,
    COUNT(*) AS count,
    s.completed_at AS scan_date
FROM vulnerabilities v
JOIN scan_results s ON s.id = v.scan_result_id
GROUP BY v.scan_result_id, s.type, s.target, v.severity, s.completed_at;


-- 6. View tổng hợp audit log theo ngày
CREATE OR REPLACE VIEW v_daily_audit_summary AS
SELECT 
    DATE(created_at) AS log_date,
    event_type,
    COUNT(*) AS event_count,
    COUNT(DISTINCT user_id) AS unique_users
FROM audit_logs
GROUP BY DATE(created_at), event_type
ORDER BY log_date DESC;


-- 7. View đánh giá theo tháng
CREATE OR REPLACE VIEW v_monthly_evaluation_trend AS
SELECT 
    YEAR(created_at) AS year,
    MONTH(created_at) AS month,
    COUNT(*) AS total_evaluations,
    ROUND(AVG(percentage), 2) AS avg_score,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) AS approved
FROM evaluations
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY year DESC, month DESC;


-- 8. View top users theo đánh giá
CREATE OR REPLACE VIEW v_top_evaluators AS
SELECT 
    u.id AS user_id,
    u.name AS user_name,
    u.role,
    COUNT(e.id) AS evaluation_count,
    ROUND(AVG(e.percentage), 2) AS avg_score,
    MAX(e.percentage) AS best_score,
    MIN(e.percentage) AS worst_score
FROM users u
JOIN evaluations e ON e.user_id = u.id
WHERE e.status = 'approved'
GROUP BY u.id, u.name, u.role
ORDER BY evaluation_count DESC
LIMIT 50;


-- 9. View thiết bị đang hoạt động
CREATE OR REPLACE VIEW v_active_devices AS
SELECT 
    dc.device_id,
    dc.device_name,
    dc.device_type,
    u.name AS user_name,
    dc.last_connected_at,
    CASE 
        WHEN dc.last_connected_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'Online'
        WHEN dc.last_connected_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 'Away'
        ELSE 'Offline'
    END AS status
FROM device_connections dc
JOIN users u ON u.id = dc.user_id
WHERE dc.is_connected = true;


-- 10. View compliance status
CREATE OR REPLACE VIEW v_compliance_status AS
SELECT 
    cc.standard,
    cc.status,
    cc.score,
    cc.checked_at,
    u.name AS checked_by,
    cc.next_check_date,
    CASE 
        WHEN cc.next_check_date < CURDATE() THEN 'Overdue'
        WHEN cc.next_check_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 'Due Soon'
        ELSE 'OK'
    END AS reminder_status
FROM compliance_checks cc
JOIN users u ON u.id = cc.checked_by
ORDER BY cc.checked_at DESC;