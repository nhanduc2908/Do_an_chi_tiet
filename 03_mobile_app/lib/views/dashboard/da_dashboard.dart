import 'package:flutter/material.dart';

class DaDashboard extends StatelessWidget {
  const DaDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('🗄️ Data Analytics'),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Trung tâm dữ liệu',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Phân tích và trực quan hóa dữ liệu',
              style: TextStyle(color: Colors.grey[600]),
            ),
            const SizedBox(height: 24),

            // Data Stats
            GridView.count(
              crossAxisCount: 3,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              children: [
                _buildDataStat('Tổng records', '1.24M', Icons.storage),
                _buildDataStat('Nguồn dữ liệu', '12', Icons.source),
                _buildDataStat('Dashboard', '8', Icons.dashboard),
              ],
            ),
            const SizedBox(height: 24),

            // Data Quality
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('📊 Chất lượng dữ liệu', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    Center(
                      child: SizedBox(
                        width: 120,
                        height: 120,
                        child: Stack(
                          alignment: Alignment.center,
                          children: [
                            const CircularProgressIndicator(value: 0.945, strokeWidth: 10, color: Colors.green),
                            Column(
                              mainAxisSize: MainAxisSize.min,
                              children: const [
                                Text('94.5%', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                                Text('Quality', style: TextStyle(fontSize: 12)),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    _buildQualityBar('Completeness', 98, Colors.green),
                    _buildQualityBar('Accuracy', 96, Colors.blue),
                    _buildQualityBar('Consistency', 92, Colors.orange),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDataStat(String title, String value, IconData icon) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          children: [
            Icon(icon, size: 24, color: Colors.blue),
            const SizedBox(height: 8),
            Text(value, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            Text(title, style: const TextStyle(fontSize: 11, color: Colors.grey)),
          ],
        ),
      ),
    );
  }

  Widget _buildQualityBar(String title, int percent, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [Text(title), Text('$percent%', style: TextStyle(color: color, fontWeight: FontWeight.bold))],
          ),
          const SizedBox(height: 4),
          LinearProgressIndicator(value: percent / 100, backgroundColor: Colors.grey[200], color: color),
        ],
      ),
    );
  }
}