import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class BaDashboard extends StatelessWidget {
  const BaDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: const Text('📊 Business Analytics'),
        centerTitle: true,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Tổng quan kinh doanh',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Phân tích dữ liệu và KPI',
              style: TextStyle(color: Colors.grey[600]),
            ),
            const SizedBox(height: 24),

            // KPI Cards
            GridView.count(
              crossAxisCount: 2,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              children: [
                _buildKpiCard('Doanh thu tháng', '45.2M', '+15.3%', Icons.trending_up, Colors.green),
                _buildKpiCard('Lợi nhuận ròng', '12.8M', '+8.2%', Icons.attach_money, Colors.blue),
                _buildKpiCard('Khách hàng mới', '2,456', '+23%', Icons.people, Colors.purple),
                _buildKpiCard('Tỷ lệ chuyển đổi', '3.24%', '+0.5%', Icons.percent, Colors.orange),
              ],
            ),
            const SizedBox(height: 24),

            // Revenue Chart
            Card(
              elevation: 2,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('📈 Doanh thu theo tháng', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    SizedBox(
                      height: 200,
                      child: LineChart(
                        LineChartData(
                          gridData: const GridData(show: true),
                          titlesData: const FlTitlesData(show: true),
                          borderData: FlBorderData(show: true),
                          lineBarsData: [
                            LineChartBarData(
                              spots: const [
                                FlSpot(0, 32), FlSpot(1, 35), FlSpot(2, 38),
                                FlSpot(3, 42), FlSpot(4, 45), FlSpot(5, 48),
                                FlSpot(6, 52), FlSpot(7, 55), FlSpot(8, 58),
                                FlSpot(9, 62), FlSpot(10, 65), FlSpot(11, 68),
                              ],
                              isCurved: true,
                              color: Colors.blue,
                              barWidth: 3,
                              dotData: const FlDotData(show: true),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Top Products
            Card(
              elevation: 2,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('🏆 Sản phẩm bán chạy', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    _buildProductItem('Product A - Premium', 85, 45200, Colors.blue),
                    _buildProductItem('Product B - Standard', 65, 32100, Colors.green),
                    _buildProductItem('Product C - Enterprise', 55, 28500, Colors.purple),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildKpiCard(String title, String value, String trend, IconData icon, Color color) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(title, style: const TextStyle(fontSize: 12, color: Colors.grey)),
                Icon(icon, color: color, size: 20),
              ],
            ),
            const SizedBox(height: 8),
            Text(value, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            Text(trend, style: TextStyle(fontSize: 11, color: trend.contains('+') ? Colors.green : Colors.red)),
          ],
        ),
      ),
    );
  }

  Widget _buildProductItem(String name, int percent, int revenue, Color color) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(name, style: const TextStyle(fontSize: 14)),
            Text('\$${revenue.toString()}', style: const TextStyle(fontWeight: FontWeight.bold)),
          ],
        ),
        const SizedBox(height: 4),
        LinearProgressIndicator(value: percent / 100, backgroundColor: Colors.grey[200], color: color, minHeight: 6),
        const SizedBox(height: 12),
      ],
    );
  }
}