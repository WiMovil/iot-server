/**
 * BasicHTTPClient.ino
 *
 *  Created on: 24.05.2015
 *
 */



#include <Arduino.h>

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>

#include <ESP8266HTTPClient.h>


ESP8266WiFiMulti WiFiMulti;

/*---------------------------------------------------------------*/


void setup() {

    Serial.begin(115200);
   // Serial.setDebugOutput(true);

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



}

void loop() {
  
    /*Actuador*/
    
    // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        Serial.print("[HTTP-ACTUADOR] begin...\n");
        // configure traged server and url
        //http.begin("https://192.168.1.12/test.html", "7a 9c f4 db 40 d3 62 5a 6e 21 bc 5c cc 66 c8 3e a1 45 59 38"); //HTTPS
        http.begin("http://10.10.10.10/tools/get_config_actuador.php?d=f899139df5e1059396431415e770c6dd"); //HTTP md5 send

        Serial.print("[HTTP-ACTUADOR] GET...\n");
        // start connection and send HTTP header
        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            Serial.printf("[HTTP-ACTUADOR] GET... code: %d\n", httpCode); // Ver contenido http-request

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();


                 if (payload.indexOf("d=310b86e0b62b828562fc91c7be5380a992b2786a") != -1){ // sha1 recive
                   
                     Serial.println(payload); //Enviar contenido por serial
                     
                 }
            }
        } else {
            Serial.printf("[HTTP-ACTUADOR] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }


         

        http.end();
    }

    delay(800);
}

