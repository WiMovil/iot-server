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

#define USE_SERIAL Serial

ESP8266WiFiMulti WiFiMulti;


/* ESP METAL
int pwm = 5;
int sw = 4;
*/

/* ESP MINI
int pwm = 2;
int sw = 3;
*/

/* ESP NodeMCU */
int sw1 = D0;
int pwm1 = D1;

String last_config;



String c = "";

void setup() {

    USE_SERIAL.begin(115200);
   // USE_SERIAL.setDebugOutput(true);

    USE_SERIAL.println();
    USE_SERIAL.println();
    USE_SERIAL.println();

    for(uint8_t t = 4; t > 0; t--) {
        USE_SERIAL.printf("[SETUP] WAIT %d...\n", t);
        USE_SERIAL.flush();
        delay(1000);
    }

    WiFi.mode(WIFI_STA);
    WiFiMulti.addAP("NPS", "NPS43210");
    
    pinMode(sw1, OUTPUT);
    pinMode(pwm1, OUTPUT);
    
    digitalWrite(sw1, LOW);
    digitalWrite(pwm1, LOW);
    

}

void loop() {
    // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        USE_SERIAL.print("[HTTP] begin...\n");
        // configure traged server and url
        //http.begin("https://192.168.1.12/test.html", "7a 9c f4 db 40 d3 62 5a 6e 21 bc 5c cc 66 c8 3e a1 45 59 38"); //HTTPS
        http.begin("http://10.10.10.10/tools/get_config_actuador.php?d=f899139df5e1059396431415e770c6dd"); //HTTP

        USE_SERIAL.print("[HTTP] GET...\n");
        // start connection and send HTTP header
        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            USE_SERIAL.printf("[HTTP] GET... code: %d\n", httpCode);

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();
                USE_SERIAL.println(payload);

                 if (payload.indexOf("d=310b86e0b62b828562fc91c7be5380a992b2786a") != -1){

                  if(last_config!=payload){

                    last_config=payload;
                  
                 
                      if (payload.indexOf("p1=0") != -1){
                        digitalWrite(sw1, LOW);
                        analogWrite(pwm1, 0);
                      }
                    
                      for(int i = 1; i <= 255; i++){
                    
                        c = "p1=";
                        c += i;
                    
                         
                        if (payload.indexOf(c) != -1){
                          digitalWrite(sw1, HIGH);
                          analogWrite(pwm1, i);
                          c = "";
                        }
                
                       }
                       
                    }else{
                      USE_SERIAL.printf("No changes");
                    }
                 }
                 
            }
        } else {
            USE_SERIAL.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }


         

        http.end();
    }

    delay(400);
}

