import 'package:flutter/material.dart';

class ReportsScreen extends StatelessWidget {
  const ReportsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Báo cáo'),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView(
        children: [
          // Filter section
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.grey.shade50,
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: () {},
                        icon: const Icon(Icons.calendar_today),
                        label: const Text('Chọn thời gian'),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: () {},
                        icon: const Icon(Icons.filter_list),
                        label: const Text('Lọc'),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text(
              'Danh sách báo cáo',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
          ),
          
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.picture_as_pdf, color: Colors.red),
              title: const Text('Báo cáo doanh thu Q4'),
              subtitle: const Text('Tạo ngày 15/12/2024 • 2.5 MB'),
              trailing: const Icon(Icons.more_vert),
              onTap: () {},
            ),
          ),
          
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.insert_chart, color: Colors.blue),
              title: const Text('Báo cáo an toàn tháng 12'),
              subtitle: const Text('Tạo ngày 14/12/2024 • 1.8 MB'),
              trailing: const Icon(Icons.more_vert),
              onTap: () {},
            ),
          ),
          
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.assessment, color: Colors.green),
              title: const Text('Báo cáo đánh giá hệ thống'),
              subtitle: const Text('Tạo ngày 10/12/2024 • 3.2 MB'),
              trailing: const Icon(Icons.more_vert),
              onTap: () {},
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        child: const Icon(Icons.add),
      ),
    );
  }
}