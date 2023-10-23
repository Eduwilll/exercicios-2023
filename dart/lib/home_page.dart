import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dadosModel.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List<Data> activities = [];

  @override
  void initState() {
    super.initState();
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
        final decodedData = DataModel.fromJson(json.decode(jsonData));

        setState(() {
          activities = decodedData.data;
        });
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
                            '${activity.start ?? 'Start Date'} - ${activity.end ?? 'End Date'}',
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 22,
                            ),
                          ),
                        ),
                        Text(
                          activity.title?.ptBr ?? 'Title not available',
                          style: TextStyle(color: Colors.grey.shade600),
                        ),
                        if (activity.people != null &&
                            activity.people.isNotEmpty)
                          Text(activity.people[0]?.name ?? 'No name')
                        else
                          Text('No people data')
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
