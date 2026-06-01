#include <WiFi.h>
#include <WebServer.h>

// Wi-Fi credentials
const char* ssid = "Spark";
const char* password = "123456789";

WebServer server(80);

// Pins for both slots
const int trigPin1 = 12, echoPin1 = 13, redLed1 = 14, yellowLed1 = 27, greenLed1 = 26;
const int trigPin2 = 4, echoPin2 = 15, redLed2 = 21, yellowLed2 = 23, greenLed2 = 22;

// Reservation state
unsigned long slot1End = 0, slot2End = 0;
bool slot1Reserved = false, slot2Reserved = false;

// Distance function
float readDistanceCM(int trig, int echo) {
  digitalWrite(trig, LOW);
  delayMicroseconds(2);
  digitalWrite(trig, HIGH);
  delayMicroseconds(10);
  digitalWrite(trig, LOW);
  long duration = pulseIn(echo, HIGH, 30000);
  float distance = duration * 0.034 / 2;
  if (distance == 0 || distance > 400) distance = 400;
  return distance;
}

void setup() {
  Serial.begin(115200);

  pinMode(trigPin1, OUTPUT); pinMode(echoPin1, INPUT);
  pinMode(trigPin2, OUTPUT); pinMode(echoPin2, INPUT);

  pinMode(redLed1, OUTPUT); pinMode(yellowLed1, OUTPUT); pinMode(greenLed1, OUTPUT);
  pinMode(redLed2, OUTPUT); pinMode(yellowLed2, OUTPUT); pinMode(greenLed2, OUTPUT);

  digitalWrite(greenLed1, HIGH);
  digitalWrite(greenLed2, HIGH);

  Serial.println("Connecting to Wi-Fi...");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to Wi-Fi!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());

  // Slot 1 reservation endpoint
  server.on("/slot1/on", []() {
    int duration = server.arg("duration").toInt();
    if (duration <= 0) duration = 5; // default 5 mins

    slot1Reserved = true;
    slot1End = millis() + (unsigned long)duration * 60000UL;

    digitalWrite(yellowLed1, HIGH);
    digitalWrite(redLed1, LOW);
    digitalWrite(greenLed1, LOW);

    Serial.println("Slot 1 reserved for " + String(duration) + " minutes.");
    server.send(200, "text/plain", "Slot 1 reserved for " + String(duration) + " minutes.");
  });

  // Slot 2 reservation endpoint
  server.on("/slot2/on", []() {
    int duration = server.arg("duration").toInt();
    if (duration <= 0) duration = 5;

    slot2Reserved = true;
    slot2End = millis() + (unsigned long)duration * 60000UL;

    digitalWrite(yellowLed2, HIGH);
    digitalWrite(redLed2, LOW);
    digitalWrite(greenLed2, LOW);

    Serial.println("Slot 2 reserved for " + String(duration) + " minutes.");
    server.send(200, "text/plain", "Slot 2 reserved for " + String(duration) + " minutes.");
  });

 

server.on("/status", []() {
  // Read LED states directly
  bool red1 = digitalRead(redLed1);
  bool yellow1 = digitalRead(yellowLed1);
  bool green1 = digitalRead(greenLed1);

  bool red2 = digitalRead(redLed2);
  bool yellow2 = digitalRead(yellowLed2);
  bool green2 = digitalRead(greenLed2);

  String slot1Status, slot2Status;

  // Determine slot1 status
  if (red1) slot1Status = "occupied";
  else if (yellow1) slot1Status = "reserved";
  else if (green1) slot1Status = "empty";
  else slot1Status = "disconnected";

  // Determine slot2 status
  if (red2) slot2Status = "occupied";
  else if (yellow2) slot2Status = "reserved";
  else if (green2) slot2Status = "empty";
  else slot2Status = "disconnected";

  String json = "{";
  json += "\"slot1\":\"" + slot1Status + "\",";
  json += "\"slot2\":\"" + slot2Status + "\"";
  json += "}";

  server.send(200, "application/json", json);
});


  server.begin();
  Serial.println("Web Server started!");
}

void loop() {
  server.handleClient();
  unsigned long now = millis();

  // Read distances
  float d1 = readDistanceCM(trigPin1, echoPin1);
  float d2 = readDistanceCM(trigPin2, echoPin2);

  bool car1 = d1 < 5;
  bool car2 = d2 < 5;

  // SLOT 1 logic
  if (slot1Reserved) {
    if (now >= slot1End) {
      slot1Reserved = false;
      digitalWrite(yellowLed1, LOW);
      digitalWrite(redLed1, LOW);
      digitalWrite(greenLed1, HIGH);
    } else {
      if (car1) {
        digitalWrite(redLed1, HIGH);
        digitalWrite(yellowLed1, LOW);
      } else {
        digitalWrite(redLed1, LOW);
        digitalWrite(yellowLed1, HIGH);
      }
      digitalWrite(greenLed1, LOW);
    }
  } else {
    if (car1) {
      digitalWrite(redLed1, HIGH);
      digitalWrite(greenLed1, LOW);
      digitalWrite(yellowLed1, LOW);
    } else {
      digitalWrite(greenLed1, HIGH);
      digitalWrite(redLed1, LOW);
      digitalWrite(yellowLed1, LOW);
    }
  }

  // SLOT 2 logic
  if (slot2Reserved) {
    if (now >= slot2End) {
      slot2Reserved = false;
      digitalWrite(yellowLed2, LOW);
      digitalWrite(redLed2, LOW);
      digitalWrite(greenLed2, HIGH);
    } else {
      if (car2) {
        digitalWrite(redLed2, HIGH);
        digitalWrite(yellowLed2, LOW);
      } else {
        digitalWrite(redLed2, LOW);
        digitalWrite(yellowLed2, HIGH);
      }
      digitalWrite(greenLed2, LOW);
    }
  } else {
    if (car2) {
      digitalWrite(redLed2, HIGH);
      digitalWrite(greenLed2, LOW);
      digitalWrite(yellowLed2, LOW);
    } else {
      digitalWrite(greenLed2, HIGH);
      digitalWrite(redLed2, LOW);
      digitalWrite(yellowLed2, LOW);
    }
  }

  delay(300);
}
