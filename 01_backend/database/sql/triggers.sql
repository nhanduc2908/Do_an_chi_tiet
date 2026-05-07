-- =====================================================
-- TRIGGERS - CÁC TRIGGER TỰ ĐỘNG
-- =====================================================

DELIMITER //

-- 1. Trigger cập nhật updated_at khi sửa bản ghi
CREATE TRIGGER trg_users_update 
    BEFORE UPDATE ON users
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = NOW();
END //

CREATE TRIGGER trg_evaluations_update 
    BEFORE UPDATE ON evaluations
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = NOW();
END //

-- 2. Trigger tự động tạo audit log khi thêm mới evaluation
CREATE TRIGGER trg_evaluations_insert_audit
    AFTER INSERT ON evaluations
    FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, event_type, description, ip_address, created_at)
    VALUES (NEW.user_id, 'create', CONCAT('Tạo đánh giá mới: ', NEW.title), 
            (SELECT ip_address FROM sessions WHERE session_id = CURRENT_SESSION_ID()), NOW());
END //

-- 3. Trigger cập nhật tổng điểm khi sửa evaluation_detail
CREATE TRIGGER trg_evaluation_detail_update_score
    AFTER UPDATE ON evaluation_details
    FOR EACH ROW
BEGIN
    DECLARE total_score DECIMAL(10,2);
    DECLARE max_score DECIMAL(10,2);
    DECLARE percentage_val DECIMAL(5,2);
    
    SELECT SUM(score), SUM(c.weight) INTO total_score, max_score
    FROM evaluation_details ed
    JOIN criteria c ON c.id = ed.criteria_id
    WHERE ed.evaluation_id = NEW.evaluation_id;
    
    SET percentage_val = IF(max_score > 0, (total_score / max_score) * 100, 0);
    
    UPDATE evaluations 
    SET total_score = total_score, 
        max_score = max_score, 
        percentage = percentage_val
    WHERE id = NEW.evaluation_id;
END //

-- 4. Trigger ghi log khi xóa bản ghi
CREATE TRIGGER trg_users_delete_log
    BEFORE DELETE ON users
    FOR EACH ROW
BEGIN
    INSERT INTO data_change_logs (table_name, record_id, action, old_data, created_at)
    VALUES ('users', OLD.id, 'delete', 
            JSON_OBJECT('name', OLD.name, 'email', OLD.email), NOW());
END //

-- 5. Trigger tự động cập nhật last_login
CREATE TRIGGER trg_login_history_insert
    AFTER INSERT ON login_histories
    FOR EACH ROW
BEGIN
    IF NEW.status = 'success' THEN
        UPDATE users SET last_login_at = NEW.login_at, last_login_ip = NEW.ip_address
        WHERE id = NEW.user_id;
    END IF;
END //

-- 6. Trigger kiểm tra quyền thay đổi role
CREATE TRIGGER trg_check_role_change
    BEFORE UPDATE ON users
    FOR EACH ROW
BEGIN
    IF OLD.role != NEW.role THEN
        INSERT INTO audit_logs (user_id, event_type, description, created_at)
        VALUES (NEW.id, 'role_change', 
                CONCAT('Thay đổi role từ ', OLD.role, ' sang ', NEW.role), NOW());
    END IF;
END //

-- 7. Trigger cập nhật số lượng đánh giá cho domain
CREATE TRIGGER trg_evaluations_insert_count
    AFTER INSERT ON evaluations
    FOR EACH ROW
BEGIN
    UPDATE domains SET evaluation_count = evaluation_count + 1 
    WHERE id = NEW.domain_id;
END //

-- 8. Trigger giảm số lượng khi xóa đánh giá
CREATE TRIGGER trg_evaluations_delete_count
    AFTER DELETE ON evaluations
    FOR EACH ROW
BEGIN
    UPDATE domains SET evaluation_count = evaluation_count - 1 
    WHERE id = OLD.domain_id;
END //

DELIMITER ;