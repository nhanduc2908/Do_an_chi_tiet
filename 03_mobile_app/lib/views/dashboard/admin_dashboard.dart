import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../providers/device_provider.dart';
import '../../shared/widgets/stat_card.dart';
import '../../shared/widgets/activity_item.dart';

class AdminDashboard extends StatelessWidget {
  const AdminDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final deviceProvider = context.watch<DeviceProvider>();
    final user = authProvider.user;

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: const Text('👑 Admin Dashboard'),
        centerTitle: true,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => deviceProvider.loadDevices(),
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome Section
              Row(
                children: [
                  CircleAvatar(
                    radius: 24,
                    backgroundColor: Colors.blue.shade100,
                    child: Text(
                      user?.avatarLetter ?? 'A',
                      style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.blue),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Xin chào, ${user?.name ?? 'Admin'}!',
                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                        ),
                        Text(
                          'Quản trị toàn bộ hệ thống',
                          style: TextStyle(color: Colors.grey[600], fontSize: 14),
                        ),
                      ],
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade50,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      user?.roleDisplay ?? 'Admin',
                      style: TextStyle(color: Colors.blue.shade700, fontWeight: FontWeight.w500),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),

              // Stats Grid
              GridView.count(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                children: [
                  const StatCard(title: 'Tổng người dùng', value: '1,234', icon: Icons.people, color: Colors.blue, trend: '+12%'),
                  const StatCard(title: 'Hoạt động hôm nay', value: '456', icon: Icons.trending_up, color: Colors.green, trend: '+8%'),
                  StatCard(
                    title: 'Thiết bị trực tuyến',
                    value: '${deviceProvider.onlineCount}/${deviceProvider.deviceCount}',
                    icon: Icons.devices,
                    color: Colors.orange,
                    trend: '${deviceProvider.onlineCount} đang hoạt động',
                  ),
                  const StatCard(title: 'Cảnh báo bảo mật', value: '3', icon: Icons.warning, color: Colors.red, trend: 'Cần xử lý'),
                ],
              ),
              const SizedBox(height: 24),

              // System Health
              Card(
                elevation: 2,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Row(
                        children: [
                          Icon(Icons.health_and_safety, color: Colors.blue),
                          SizedBox(width: 8),
                          Text('🖥️ Sức khỏe hệ thống', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const SizedBox(height: 16),
                      _buildHealthBar('CPU Usage', 45, Colors.blue),
                      const SizedBox(height: 12),
                      _buildHealthBar('Memory Usage', 40, Colors.green),
                      const SizedBox(height: 12),
                      _buildHealthBar('Disk Usage', 49, Colors.orange),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Recent Activities
              Card(
                elevation: 2,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Row(
                        children: [
                          Icon(Icons.history, color: Colors.blue),
                          SizedBox(width: 8),
                          Text('📋 Hoạt động gần đây', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const SizedBox(height: 16),
                      const ActivityItem(title: 'Người dùng mới: Nguyễn Văn A', time: '5 phút trước', icon: Icons.person_add, iconColor: Colors.green),
                      const ActivityItem(title: 'Backup database hoàn tất', time: '1 giờ trước', icon: Icons.backup, iconColor: Colors.blue),
                      const ActivityItem(title: 'Phát hiện đăng nhập bất thường', time: '3 giờ trước', icon: Icons.warning, iconColor: Colors.orange),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Quick Actions
              Card(
                elevation: 2,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Row(
                        children: [
                          Icon(Icons.bolt, color: Colors.amber),
                          SizedBox(width: 8),
                          Text('⚡ Thao tác nhanh', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Wrap(
                        spacing: 12,
                        runSpacing: 12,
                        children: [
                          _buildActionButton('Thêm người dùng', Icons.person_add, Colors.blue, () {
                            Navigator.pushNamed(context, '/users/create');
                          }),
                          _buildActionButton('Sao lưu dữ liệu', Icons.backup, Colors.green, () {}),
                          _buildActionButton('Cài đặt bảo mật', Icons.security, Colors.red, () {}),
                          _buildActionButton('Xem nhật ký', Icons.history, Colors.orange, () {}),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHealthBar(String title, int percent, Color color) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: const TextStyle(fontSize: 14)),
            Text('$percent%', style: TextStyle(color: color, fontWeight: FontWeight.bold)),
          ],
        ),
        const SizedBox(height: 4),
        LinearProgressIndicator(
          value: percent / 100,
          backgroundColor: Colors.grey[200],
          color: color,
          minHeight: 8,
          borderRadius: BorderRadius.circular(4),
        ),
      ],
    );
  }

  Widget _buildActionButton(String label, IconData icon, Color color, VoidCallback onTap) {
    return ElevatedButton.icon(
      onPressed: onTap,
      icon: Icon(icon, size: 18),
      label: Text(label),
      style: ElevatedButton.styleFrom(
        backgroundColor: color.withOpacity(0.1),
        foregroundColor: color,
        elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
    );
  }
}