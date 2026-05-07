class ReportModel {
  final String id;
  final String title;
  final String type;
  final String format;
  final int size;
  final DateTime createdAt;
  final String? downloadUrl;
  final String? createdBy;
  final Map<String, dynamic>? data;

  ReportModel({
    required this.id,
    required this.title,
    required this.type,
    required this.format,
    required this.size,
    required this.createdAt,
    this.downloadUrl,
    this.createdBy,
    this.data,
  });

  factory ReportModel.fromJson(Map<String, dynamic> json) {
    return ReportModel(
      id: json['id'].toString(),
      title: json['title'] ?? '',
      type: json['type'] ?? 'general',
      format: json['format'] ?? 'pdf',
      size: json['size'] ?? 0,
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      downloadUrl: json['download_url'],
      createdBy: json['created_by'],
      data: json['data'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'type': type,
      'format': format,
      'size': size,
      'created_at': createdAt.toIso8601String(),
      'download_url': downloadUrl,
      'created_by': createdBy,
      'data': data,
    };
  }

  String get formattedSize {
    if (size < 1024) return '$size B';
    if (size < 1024 * 1024) return '${(size / 1024).toStringAsFixed(1)} KB';
    return '${(size / (1024 * 1024)).toStringAsFixed(1)} MB';
  }

  String get formattedDate {
    return '${createdAt.day}/${createdAt.month}/${createdAt.year}';
  }

  String get typeText {
    switch (type) {
      case 'security': return 'Báo cáo bảo mật';
      case 'evaluation': return 'Báo cáo đánh giá';
      case 'user': return 'Báo cáo người dùng';
      case 'system': return 'Báo cáo hệ thống';
      default: return 'Báo cáo';
    }
  }

  IconData get typeIcon {
    switch (type) {
      case 'security': return Icons.security;
      case 'evaluation': return Icons.assessment;
      case 'user': return Icons.people;
      case 'system': return Icons.settings;
      default: return Icons.description;
    }
  }

  Color get typeColor {
    switch (type) {
      case 'security': return Colors.red;
      case 'evaluation': return Colors.blue;
      case 'user': return Colors.green;
      case 'system': return Colors.purple;
      default: return Colors.grey;
    }
  }
  
  String get formatIcon {
    switch (format.toLowerCase()) {
      case 'pdf': return '📄';
      case 'excel': return '📊';
      case 'csv': return '📈';
      default: return '📁';
    }
  }
}