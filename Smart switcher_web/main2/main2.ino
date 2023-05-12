//----------------------------------------Include the NodeMCU ESP8266 Library
//----------------------------------------see here: https://www.youtube.com/watch?v=8jMr94B8iN0 to add NodeMCU ESP8266 library and board
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>
WiFiClient wifiClient;
//----------------------------------------

#define ON_Board_LED   //--> Defining an On Board LED (GPIO2 = D4), used for indicators when the process of connecting to a wifi router

#define RELAY 0 //--> Defines an LED Pin. D8 = GPIO15

//----------------------------------------SSID and Password of your WiFi router.
const char* ssid = "MyhomeRouter"; //--> Your wifi name or SSID.
const char* password = "29764298"; //--> Your wifi password.

//----------------------------------------

//----------------------------------------Web Server address / IPv4
// If using IPv4, press Windows key + R then type cmd, then type ipconfig (If using Windows OS).
const char *host = "http://aiot209.000webhostapp.com/";
//----------------------------------------

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  delay(500);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password); //--> Connect to your WiFi router
  Serial.println("");
    
  pinMode(RELAY,OUTPUT); //--> LED port Direction output
  digitalWrite(RELAY, LOW); //--> Turn off Led

  //----------------------------------------Wait for connection
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print("."); }
  //----------------------------------------If successfully connected to the wifi router, the IP Address that will be visited is displayed in the serial monitor
  Serial.println("");
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println();
  //----------------------------------------
}

void loop() {
  // put your main code here, to run repeatedly:
  HTTPClient http; //--> Declare object of class HTTPClient

  //----------------------------------------Getting Data from MySQL Database
  String GetAddress, LinkGet, getData, LEDStatResultSend;;
  int id = 0; //--> ID in Database
  GetAddress = "data/GetData.php"; 
  LinkGet = host + GetAddress; //--> Make a Specify request destination
  getData = "ID=" + String(id);
  Serial.println("----------------Connect to Server-----------------");
  Serial.println("Get Status from Server or Database");
  Serial.print("Request Link : ");
  Serial.println(LinkGet);
  http.begin(wifiClient, LinkGet); //--> Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
  int httpCodeGet = http.POST(getData); //--> Send the request
  String payloadGet = http.getString(); //--> Get the response payload from server
  Serial.print("Response Code : "); //--> If Response Code = 200 means Successful connection, if -1 means connection failed. For more information see here : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
  Serial.println(httpCodeGet); //--> Print HTTP return code
  Serial.print("Returned data from Server : ");
  Serial.println(payloadGet); //--> Print request response payload

  if (payloadGet == "1") {
    digitalWrite(RELAY, HIGH); //--> Turn on Relay
    LEDStatResultSend = payloadGet;
  }
  if (payloadGet == "0") {
    digitalWrite(RELAY, LOW); //--> Turn off Relay
    LEDStatResultSend = payloadGet;
  }
  //----------------------------------------
  
  //----------------------------------------Sends LED status feedback data to server
  Serial.println();
  Serial.println("Sending LED Status to Server");
  String postData, LinkSend, SendAddress;
  SendAddress = "data/getLEDStatFromNodeMCU.php";
  LinkSend = host + SendAddress;
  postData = "getLEDStatusFromNodeMCU=" + LEDStatResultSend;
  Serial.print("Request Link : ");
  Serial.println(LinkSend);
  http.begin(wifiClient, LinkSend); //--> Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded"); //--> Specify content-type header
  int httpCodeSend = http.POST(postData); //--> Send the request
  String payloadSend = http.getString(); //--> Get the response payload
  Serial.print("Response Code : "); //--> If Response Code = 200 means Successful connection, if -1 means connection failed
  Serial.println(httpCodeSend); //--> Print HTTP return code
  Serial.print("Returned data from Server : ");
  Serial.println(payloadSend); //--> Print request response payload
  //----------------------------------------
  
  Serial.println("----------------Closing Connection----------------");
  http.end(); //--> Close connection
  Serial.println();
  Serial.println("Please wait 2 seconds for the next connection.");
  Serial.println();
  delay(1000); //--> GET Data at every 3 seconds
}
