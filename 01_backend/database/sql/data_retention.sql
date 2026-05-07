-- =====================================================
-- DATA RETENTION - CHÍNH SÁCH LƯU TRỮ DỮ LIỆU
-- =====================================================

-- 1. Xóa login history cũ hơn 90 ngày
DELETE FROM login_histories 
WHERE login_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- 2. Xóa audit logs cũ hơn 365 ngày
DELETE FROM audit_logs 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 365 DAY);

-- 3. Xóa session logs cũ hơn 30 ngày
DELETE FROM session_logs 
WHERE login_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- 4. Xóa notifications đã đọc cũ hơn 30 ngày
DELETE FROM notifications 
WHERE is_read = true AND read_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- 5. Xóa sync_queues đã hoàn thành cũ hơn 7 ngày
DELETE FROM sync_queues 
WHERE status = 'completed' AND processed_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- 6. Xóa screen_logs cũ hơn 30 ngày
DELETE FROM screen_logs 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- 7. Xóa device_messages đã đọc cũ hơn 7 ngày
DELETE FROM device_messages 
WHERE is_read = true AND read_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- 8. Xóa temporary files cũ hơn 24 giờ
DELETE FROM files 
WHERE is_public = false AND created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);