class EvaluationModel {
  final String id;
  final String title;
  final String description;
  final double score;
  final String status;
  final DateTime createdAt;
  final String? evaluatedBy;
  final String? evaluatedById;
  final List<String>? categories;
  final Map<String, dynamic>? details;

  EvaluationModel({
    required this.id,
    required this.title,
    required this.description,
    required this.score,
    required this.status,
    required this.createdAt,
    this.evaluatedBy,
    this.evaluatedById,
    this.categories,
    this.details,
  });

  factory EvaluationModel.fromJson(Map<String, dynamic> json) {
    return EvaluationModel(
      id: json['id'].toString(),
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      score: (json['score'] ?? 0).toDouble(),
      status: json['status'] ?? 'pending',
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      evaluatedBy: json['evaluated_by'],
      evaluatedById: json['evaluated_by_id'],
      categories: json['categories'] != null ? List<String>.from(json['categories']) : null,
      details: json['details'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'score': score,
      'status': status,
      'created_at': createdAt.toIso8601String(),
      'evaluated_by': evaluatedBy,
      'evaluated_by_id': evaluatedById,
      'categories': categories,
      'details': details,
    };
  }

  String get scoreText {
    if (score >= 8.5) return 'Xuất sắc';
    if (score >= 7.0) return 'Tốt';
    if (score >= 5.0) return 'Trung bình';
    return 'Cần cải thiện';
  }

  Color get scoreColor {
    if (score >= 8.5) return Colors.green;
    if (score >= 7.0) return Colors.blue;
    if (score >= 5.0) return Colors.orange;
    return Colors.red;
  }

  String get statusText {
    switch (status) {
      case 'completed': return 'Hoàn thành';
      case 'pending': return 'Đang chờ';
      case 'in_progress': return 'Đang tiến hành';
      case 'cancelled': return 'Đã hủy';
      default: return status;
    }
  }

  Color get statusColor {
    switch (status) {
      case 'completed': return Colors.green;
      case 'pending': return Colors.orange;
      case 'in_progress': return Colors.blue;
      case 'cancelled': return Colors.red;
      default: return Colors.grey;
    }
  }

  String get formattedDate {
    return '${createdAt.day}/${createdAt.month}/${createdAt.year}';
  }
  
  double get scorePercent => score / 10 * 100;
}