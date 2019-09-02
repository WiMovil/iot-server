#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
ESP8266WiFiMulti WiFiMulti;


/*-----------------------------------------*/

/* ESP lite */
int act1 = 2;




void setup() {

    Serial.begin(115200);
   // Serial.setDebugOutput(true);

    pinMode(act1,OUTPUT);

    Serial.println();
    Serial.println();
    Serial.println();

    for(uint8_t t = 4; t > 0; t--) {
        Serial.printf("[SETUP] WAIT %d...\n", t);
        Serial.flush();
        delay(1000);
    }

    WiFi.mode(WIFI_STA);
    WiFiMulti.addAP("NPS", "NPS43210");



    Serial.println();
    Serial.println("STARTED");

}

void loop() {
 
  actuador();
  
}




void actuador(){

   // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        Serial.print("[HTTP-ACTUADOR] begin...\n");

        http.begin("http://10.10.10.10/tools/get_config_actuador.php?d=38b3eff8baf56627478ec76a704e9b52"); //HTTP md5 send

        Serial.print("[HTTP-ACTUADOR] GET...\n");

        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            Serial.printf("[HTTP-ACTUADOR] GET... code: %d\n", httpCode); // Ver contenido http-request

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();


                 if (payload.indexOf("d=dbc0f004854457f59fb16ab863a3a1722cef553f") != -1){ // sha1 recive
                   
                     if (payload.indexOf("p1=255") != -1){
        					   digitalWrite(act1,LOW);
        				   }
        				   if (payload.indexOf("p1=0") != -1){
        					   digitalWrite(act1,HIGH);;
        				   }
                             
                         }
                    }
        } else {
            Serial.printf("[HTTP-ACTUADOR] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }


         

        http.end();
    }

    delay(200);
}

