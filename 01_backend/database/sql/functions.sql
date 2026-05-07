-- =====================================================
-- FUNCTIONS - CÁC HÀM TÙY CHỈNH
-- =====================================================

DELIMITER //

-- 1. Hàm tính điểm trung bình của người dùng
CREATE FUNCTION fn_user_avg_score(user_id_param INT) 
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE avg_score DECIMAL(5,2);
    
    SELECT ROUND(AVG(percentage), 2) INTO avg_score
    FROM evaluations
    WHERE user_id = user_id_param AND status = 'approved';
    
    RETURN IFNULL(avg_score, 0);
END //

-- 2. Hàm lấy tên role theo cấp độ
CREATE FUNCTION fn_role_name_by_level(level_param INT) 
RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE role_name VARCHAR(50);
    
    SELECT display_name INTO role_name
    FROM roles
    WHERE level = level_param;
    
    RETURN IFNULL(role_name, 'Unknown');
END //

-- 3. Hàm tính tuổi của đánh giá (ngày)
CREATE FUNCTION fn_evaluation_age(evaluation_id_param INT) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE age INT;
    
    SELECT DATEDIFF(NOW(), created_at) INTO age
    FROM evaluations
    WHERE id = evaluation_id_param;
    
    RETURN IFNULL(age, 0);
END //

-- 4. Hàm kiểm tra user có phải admin không
CREATE FUNCTION fn_is_admin(user_id_param INT) 
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE user_role VARCHAR(50);
    
    SELECT role INTO user_role
    FROM users
    WHERE id = user_id_param;
    
    RETURN user_role = 'admin';
END //

-- 5. Hàm tính tỷ lệ hoàn thành đánh giá của công ty
CREATE FUNCTION fn_company_completion_rate(company_id_param INT) 
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE rate DECIMAL(5,2);
    
    SELECT ROUND(
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2
    ) INTO rate
    FROM evaluations
    WHERE company_id = company_id_param;
    
    RETURN IFNULL(rate, 0);
END //

-- 6. Hàm lấy số lượng thiết bị đang hoạt động của user
CREATE FUNCTION fn_user_active_devices(user_id_param INT) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE device_count INT;
    
    SELECT COUNT(*) INTO device_count
    FROM device_connections
    WHERE user_id = user_id_param AND is_connected = true;
    
    RETURN IFNULL(device_count, 0);
END //

-- 7. Hàm tính mức độ rủi ro dựa trên điểm số
CREATE FUNCTION fn_risk_level_by_score(score_param DECIMAL(5,2)) 
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    DECLARE level VARCHAR(20);
    
    SET level = CASE
        WHEN score_param >= 90 THEN 'Rất thấp'
        WHEN score_param >= 75 THEN 'Thấp'
        WHEN score_param >= 60 THEN 'Trung bình'
        WHEN score_param >= 40 THEN 'Cao'
        ELSE 'Rất cao'
    END;
    
    RETURN level;
END //

-- 8. Hàm tính điểm trung bình theo domain
CREATE FUNCTION fn_domain_avg_score(domain_id_param INT) 
RETURNS DECIMAL(5,2)
DETERMINISTIC
BEGIN
    DECLARE avg_score DECIMAL(5,2);
    
    SELECT ROUND(AVG(percentage), 2) INTO avg_score
    FROM evaluations
    WHERE domain_id = domain_id_param AND status = 'approved';
    
    RETURN IFNULL(avg_score, 0);
END //

DELIMITER ;