#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Servo.h>

const char* ssid = "25";
const char* password = "wmtk0025";
Servo myServo;

const int trigPin = 2;
const int echoPin = 0;
const int buzzerPin = 5;
const int redLEDPin = 15;
const int greenLEDPin = 4;
int servoPin = 14;

unsigned long previousMillis = 0;
const long interval = 60000;

unsigned long buzzerStartMillis = 0;
const long buzzerDuration = 3000;
bool isBuzzerOn = false;

void setup() {
  Serial.begin(115200);
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println(WiFi.localIP());
  myServo.attach(servoPin);
  myServo.write(0);

  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);

  pinMode(buzzerPin, OUTPUT);
  digitalWrite(buzzerPin, LOW);

  pinMode(redLEDPin, OUTPUT);
  pinMode(greenLEDPin, OUTPUT);

  digitalWrite(redLEDPin, LOW);
  digitalWrite(greenLEDPin, LOW);
}

long duration;

String getValue(String data, char separator, int index) {
  int found = 0;
  int strIndex[2] = {0, -1};
  int maxIndex = data.length() - 1;

  for (int i = 0; i <= maxIndex && found <= index; i++) {
    if (data.charAt(i) == separator || i == maxIndex) {
      found++;
      strIndex[0] = strIndex[1] + 1;
      strIndex[1] = (i == maxIndex) ? i + 1 : i;
    }
  }

  return found > index ? data.substring(strIndex[0], strIndex[1]) : "";
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, "http://luqman.cloud/pakan_iwak/koneksi.php");
    int httpCode = http.GET();
    if (httpCode > 0) {
      String status = http.getString();
      Serial.print("Servo --> ");
      String nilai = getValue(status, ',', 0);
      Serial.println(nilai);
      Serial.println("------------------------");
      if (nilai == "1") {
        myServo.write(90);
        digitalWrite(greenLEDPin, HIGH);
        digitalWrite(redLEDPin, LOW);
      } else {
        myServo.write(0);
        digitalWrite(greenLEDPin, LOW);
        digitalWrite(redLEDPin, HIGH);
      }
    }
    http.end();
  } else {
    Serial.println("Delay...");
  }
  delay(500);

  unsigned long currentMillis = millis();

  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;

    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);

    duration = pulseIn(echoPin, HIGH);

    float distanceCm = duration * 0.034 / 2;

    Serial.print("Distance (cm): ");
    Serial.println(distanceCm);

    if (distanceCm > 13.6) {
      digitalWrite(buzzerPin, HIGH);
      buzzerStartMillis = currentMillis;
      isBuzzerOn = true;
    }

    if (WiFi.status() == WL_CONNECTED) {
      WiFiClient client;
      HTTPClient http;
      String serverPath = "http://luqman.cloud/pakan_iwak/koneksi.php?jarak=" + String(distanceCm);
      http.begin(client, serverPath);
      int httpCode = http.GET();
      if (httpCode > 0) {
        String payload = http.getString();
        Serial.println(payload);
      }
      http.end();
    }
  }

  if (isBuzzerOn && (currentMillis - buzzerStartMillis >= buzzerDuration)) {
    digitalWrite(buzzerPin, LOW);
    isBuzzerOn = false;
  }
}
