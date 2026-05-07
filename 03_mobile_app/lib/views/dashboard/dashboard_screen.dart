import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/auth_provider.dart';
import '../../shared/widgets/stat_card.dart';
import '../../shared/widgets/activity_item.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final user = authProvider.user;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard'),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => authProvider.logout(),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          // Refresh data
          await Future.delayed(const Duration(seconds: 1));
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome message
              Text(
                'Xin chào, ${user?.name ?? "User"}!',
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Chào mừng đến với hệ thống đánh giá an toàn',
                style: TextStyle(color: Colors.grey[600]),
              ),
              const SizedBox(height: 24),
              
              // Stats grid
              GridView.count(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                children: [
                  const StatCard(
                    title: 'Thiết bị',
                    value: '8',
                    icon: Icons.devices,
                    color: Colors.blue,
                  ),
                  const StatCard(
                    title: 'Đánh giá',
                    value: '156',
                    icon: Icons.assessment,
                    color: Colors.green,
                  ),
                  const StatCard(
                    title: 'Báo cáo',
                    value: '24',
                    icon: Icons.report,
                    color: Colors.orange,
                  ),
                  const StatCard(
                    title: 'Cảnh báo',
                    value: '3',
                    icon: Icons.warning,
                    color: Colors.red,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              
              // Recent activities
              Card(
                elevation: 2,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Hoạt động gần đây',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 12),
                      const ActivityItem(
                        title: 'Đăng nhập thành công',
                        time: '5 phút trước',
                        icon: Icons.login,
                        iconColor: Colors.green,
                      ),
                      const ActivityItem(
                        title: 'Tạo báo cáo mới',
                        time: '1 giờ trước',
                        icon: Icons.report,
                        iconColor: Colors.blue,
                      ),
                      const ActivityItem(
                        title: 'Cập nhật thiết bị',
                        time: '3 giờ trước',
                        icon: Icons.devices,
                        iconColor: Colors.orange,
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
}