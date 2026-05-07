import 'package:flutter/material.dart';

class ManagerDashboard extends StatelessWidget {
  const ManagerDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('📈 Manager Dashboard'),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Team Management',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Quản lý đội nhóm và dự án',
              style: TextStyle(color: Colors.grey[600]),
            ),
            const SizedBox(height: 24),

            GridView.count(
              crossAxisCount: 4,
              crossAxisSpacing: 8,
              mainAxisSpacing: 8,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              children: [
                _buildManagerStat('Projects', '8', Icons.project, Colors.blue),
                _buildManagerStat('Tasks', '23', Icons.task, Colors.green),
                _buildManagerStat('Team', '15', Icons.people, Colors.orange),
                _buildManagerStat('Satisfaction', '4.8', Icons.star, Colors.purple),
              ],
            ),
            const SizedBox(height: 24),

            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('✅ Team Tasks', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    _buildTaskItem('Complete Q4 report', 'Due: Tomorrow', false),
                    _buildTaskItem('Client meeting preparation', 'Due: Dec 20', true),
                    _buildTaskItem('Update documentation', 'Due: Dec 18', false),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildManagerStat(String title, String value, IconData icon, Color color) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: Column(
          children: [
            Icon(icon, color: color, size: 24),
            const SizedBox(height: 4),
            Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            Text(title, style: const TextStyle(fontSize: 10, color: Colors.grey)),
          ],
        ),
      ),
    );
  }

  Widget _buildTaskItem(String title, String dueDate, bool isUrgent) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Checkbox(value: false, onChanged: (_) {}),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: TextStyle(decoration: false ? TextDecoration.lineThrough : null)),
                Text(dueDate, style: TextStyle(fontSize: 12, color: isUrgent ? Colors.red : Colors.grey[600])),
              ],
            ),
          ),
        ],
      ),
    );
  }
}