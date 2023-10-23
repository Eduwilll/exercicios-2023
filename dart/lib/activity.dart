import 'dataModel.dart';

class Activity {
  String start;
  String end;
  String title;
  List<String> people;

  Activity({
    required this.start,
    required this.end,
    required this.title,
    required this.people,
  });

  factory Activity.fromJson(Map<String, dynamic> json) {
    return Activity(
      start: json['start'] as String? ??
          '', // Provide a default value if it's null
      end: json['end'] as String? ?? '', // Provide a default value if it's null
      title:
          json['title']['pt-br'] as String? ?? '', // Access nested properties
      people: (json['people'] as List<dynamic>?)?.map((person) {
            return person['name'] as String? ??
                ''; // Provide a default value if it's null
          }).toList() ??
          [],
    );
  }
}
