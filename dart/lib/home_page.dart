import 'package:flutter/material.dart';
import 'activity.dart'; // Importe a classe Activity
import 'dart:convert';
import 'package:flutter/services.dart';
import 'dataModel.dart';
import 'package:http/http.dart' as http;

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List<Activity> activities = [];

  @override
  void initState() {
    super.initState();
    // Carregue e analise o JSON no initState
    loadActivitiesFromJson();
  }

  Future<void> loadActivitiesFromJson() async {
    try {
      final response = await http.get(
        Uri.parse(
            "https://raw.githubusercontent.com/chuva-inc/exercicios-2023/master/dart/assets/activities.json"),
      );

      if (response.statusCode == 200) {
        final jsonData = response.body;
        final dynamic decodedData = json.decode(jsonData);

        if (decodedData is Map<String, dynamic> &&
            decodedData.containsKey('data')) {
          final List<dynamic> activityData = decodedData['data'];
          setState(() {
            activities = activityData
                .whereType<Map<String, dynamic>>() // Filter out non-maps
                .map((item) => Activity.fromJson(item))
                .toList();
          });
        }
      } else {
        print('Failed to load JSON data. Status code: ${response.statusCode}');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Event HomePage'),
      ),
      body: Center(
        child: ListView.builder(
          itemCount: activities.length,
          itemBuilder: (context, index) {
            final activity = activities[index];

            return Card(
              child: Padding(
                padding: const EdgeInsets.only(
                    top: 32, bottom: 32, left: 16, right: 16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: <Widget>[
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        InkWell(
                          onTap: () {},
                          child: Text(
                            activity.start + activity.end,
                            style: TextStyle(
                                fontWeight: FontWeight.bold, fontSize: 22),
                          ),
                        ),
                        Text(
                          activity.title,
                          style: TextStyle(color: Colors.grey.shade600),
                        ),
                        if (activity.people.isNotEmpty)
                          Text(activity.people[0])
                        else
                          Text('')
                      ],
                    ),
                  ],
                ),
              ),
            );
          },
        ),
      ),
    );
  }
}
