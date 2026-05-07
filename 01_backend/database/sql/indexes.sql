-- =====================================================
-- INDEXES - CÁC INDEX TỐI ƯU HIỆU SUẤT
-- =====================================================

-- 1. Index cho bảng users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_users_company_id ON users(company_id);
CREATE INDEX idx_users_last_login ON users(last_login_at);

-- 2. Index cho bảng evaluations
CREATE INDEX idx_evaluations_user_id ON evaluations(user_id);
CREATE INDEX idx_evaluations_domain_id ON evaluations(domain_id);
CREATE INDEX idx_evaluations_company_id ON evaluations(company_id);
CREATE INDEX idx_evaluations_status ON evaluations(status);
CREATE INDEX idx_evaluations_created_at ON evaluations(created_at);
CREATE INDEX idx_evaluations_percentage ON evaluations(percentage);
CREATE INDEX idx_evaluations_composite ON evaluations(status, created_at, percentage);

-- 3. Index cho bảng evaluation_details
CREATE INDEX idx_eval_details_evaluation_id ON evaluation_details(evaluation_id);
CREATE INDEX idx_eval_details_criteria_id ON evaluation_details(criteria_id);
CREATE INDEX idx_eval_details_score ON evaluation_details(score);

-- 4. Index cho bảng login_histories
CREATE INDEX idx_login_histories_user_id ON login_histories(user_id);
CREATE INDEX idx_login_histories_login_at ON login_histories(login_at);
CREATE INDEX idx_login_histories_status ON login_histories(status);
CREATE INDEX idx_login_histories_ip ON login_histories(ip_address);

-- 5. Index cho bảng audit_logs
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_event_type ON audit_logs(event_type);
CREATE INDEX idx_audit_logs_created_at ON audit_logs(created_at);
CREATE INDEX idx_audit_logs_composite ON audit_logs(event_type, created_at);

-- 6. Index cho bảng security_violations
CREATE INDEX idx_security_violations_user_id ON security_violations(user_id);
CREATE INDEX idx_security_violations_severity ON security_violations(severity);
CREATE INDEX idx_security_violations_resolved_at ON security_violations(resolved_at);

-- 7. Index cho bảng notifications
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
CREATE INDEX idx_notifications_priority ON notifications(priority);

-- 8. Index cho bảng device_connections
CREATE INDEX idx_device_connections_user_id ON device_connections(user_id);
CREATE INDEX idx_device_connections_device_id ON device_connections(device_id);
CREATE INDEX idx_device_connections_is_connected ON device_connections(is_connected);

-- 9. Index cho bảng sync_queues
CREATE INDEX idx_sync_queues_status ON sync_queues(status);
CREATE INDEX idx_sync_queues_priority ON sync_queues(priority);
CREATE INDEX idx_sync_queues_created_at ON sync_queues(created_at);

-- 10. Index cho bảng criteria
CREATE INDEX idx_criteria_domain_id ON criteria(domain_id);
CREATE INDEX idx_criteria_status ON criteria(status);
CREATE INDEX idx_criteria_priority ON criteria(priority);

-- 11. Index composite cho tìm kiếm
CREATE INDEX idx_evaluations_search ON evaluations(title, notes);
CREATE INDEX idx_users_search ON users(name, email);

-- 12. Index fulltext cho tìm kiếm nâng cao
CREATE FULLTEXT INDEX ft_evaluations_title ON evaluations(title);
CREATE FULLTEXT INDEX ft_users_name ON users(name);