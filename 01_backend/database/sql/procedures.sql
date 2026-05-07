-- =====================================================
-- STORED PROCEDURES - CÁC THỦ TỤC LƯU TRỮ
-- =====================================================

DELIMITER //

-- 1. Procedure tính điểm trung bình theo công ty
CREATE PROCEDURE sp_company_avg_score(IN company_id_param INT)
BEGIN
    SELECT 
        c.name AS company_name,
        ROUND(AVG(e.percentage), 2) AS avg_score,
        COUNT(e.id) AS total_evaluations
    FROM companies c
    LEFT JOIN evaluations e ON e.company_id = c.id
    WHERE c.id = company_id_param OR company_id_param IS NULL
    GROUP BY c.id, c.name;
END //

-- 2. Procedure lấy báo cáo đánh giá theo khoảng thời gian
CREATE PROCEDURE sp_evaluation_report(
    IN start_date DATE,
    IN end_date DATE,
    IN domain_id_param INT
)
BEGIN
    SELECT 
        d.name AS domain_name,
        COUNT(e.id) AS total,
        ROUND(AVG(e.percentage), 2) AS avg_score,
        MAX(e.percentage) AS max_score,
        MIN(e.percentage) AS min_score
    FROM evaluations e
    JOIN domains d ON d.id = e.domain_id
    WHERE e.created_at BETWEEN start_date AND end_date
        AND (domain_id_param IS NULL OR e.domain_id = domain_id_param)
        AND e.status = 'approved'
    GROUP BY d.id, d.name;
END //

-- 3. Procedure thống kê đăng nhập theo user
CREATE PROCEDURE sp_user_login_stats(IN user_id_param INT)
BEGIN
    SELECT 
        u.name,
        COUNT(lh.id) AS total_logins,
        SUM(CASE WHEN lh.status = 'success' THEN 1 ELSE 0 END) AS success_logins,
        SUM(CASE WHEN lh.status = 'failed' THEN 1 ELSE 0 END) AS failed_logins,
        MAX(lh.login_at) AS last_login
    FROM users u
    LEFT JOIN login_histories lh ON lh.user_id = u.id
    WHERE u.id = user_id_param OR user_id_param IS NULL
    GROUP BY u.id, u.name;
END //

-- 4. Procedure sao lưu dữ liệu đánh giá cũ
CREATE PROCEDURE sp_archive_old_evaluations(IN days_old INT)
BEGIN
    DECLARE cutoff_date DATE;
    SET cutoff_date = DATE_SUB(CURDATE(), INTERVAL days_old DAY);
    
    -- Insert vào bảng archive
    INSERT INTO evaluations_archive
    SELECT * FROM evaluations
    WHERE created_at < cutoff_date AND status = 'approved';
    
    -- Xóa khỏi bảng chính
    DELETE FROM evaluations
    WHERE created_at < cutoff_date AND status = 'approved';
END //

-- 5. Procedure cập nhật risk level cho vendor
CREATE PROCEDURE sp_update_vendor_risk_level()
BEGIN
    UPDATE vendors v
    SET v.risk_level = (
        SELECT 
            CASE 
                WHEN AVG(va.score) >= 80 THEN 'low'
                WHEN AVG(va.score) >= 60 THEN 'medium'
                WHEN AVG(va.score) >= 40 THEN 'high'
                ELSE 'critical'
            END
        FROM vendor_assessments va
        WHERE va.vendor_id = v.id
    )
    WHERE EXISTS (
        SELECT 1 FROM vendor_assessments va WHERE va.vendor_id = v.id
    );
END //

-- 6. Procedure tạo báo cáo hàng tháng
CREATE PROCEDURE sp_generate_monthly_report(IN report_month DATE)
BEGIN
    -- Báo cáo tổng quan
    SELECT 
        'Tổng quan' AS section,
        COUNT(DISTINCT u.id) AS total_users,
        COUNT(DISTINCT e.id) AS total_evaluations,
        ROUND(AVG(e.percentage), 2) AS avg_score
    FROM users u
    LEFT JOIN evaluations e ON MONTH(e.created_at) = MONTH(report_month)
        AND YEAR(e.created_at) = YEAR(report_month);
    
    -- Báo cáo theo domain
    SELECT 
        d.name AS domain,
        COUNT(e.id) AS evaluations,
        ROUND(AVG(e.percentage), 2) AS avg_score
    FROM domains d
    LEFT JOIN evaluations e ON e.domain_id = d.id
        AND MONTH(e.created_at) = MONTH(report_month)
        AND YEAR(e.created_at) = YEAR(report_month)
    GROUP BY d.id, d.name;
    
    -- Báo cáo bảo mật
    SELECT 
        severity,
        COUNT(*) AS violations,
        SUM(CASE WHEN resolved_at IS NOT NULL THEN 1 ELSE 0 END) AS resolved
    FROM security_violations
    WHERE MONTH(created_at) = MONTH(report_month)
        AND YEAR(created_at) = YEAR(report_month)
    GROUP BY severity;
END //

DELIMITER ;