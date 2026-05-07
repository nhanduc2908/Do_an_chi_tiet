import 'package:flutter/material.dart';

class DevicesScreen extends StatelessWidget {
  const DevicesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Quản lý thiết bị'),
        centerTitle: true,
      ),
      body: ListView(
        children: [
          // Connected devices
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text(
              'Thiết bị đã kết nối',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
          ),
          
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.laptop, color: Colors.blue),
              title: const Text('MacBook Pro'),
              subtitle: const Text('Đang hoạt động • 10:30 15/12/2024'),
              trailing: const Icon(Icons.check_circle, color: Colors.green),
            ),
          ),
          
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.phone_android, color: Colors.orange),
              title: const Text('iPhone 15'),
              subtitle: const Text('Không hoạt động • 21:15 14/12/2024'),
              trailing: const Icon(Icons.circle, color: Colors.grey),
            ),
          ),
          
          const SizedBox(height: 16),
          
          // Link new device button
          Padding(
            padding: const EdgeInsets.all(16),
            child: ElevatedButton.icon(
              onPressed: () {
                Navigator.pushNamed(context, '/link-device');
              },
              icon: const Icon(Icons.link),
              label: const Text('Liên kết thiết bị mới'),
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}