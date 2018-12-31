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

/*-----------------------------------------*/
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>

LiquidCrystal_I2C lcd(0x27, 16, 2);

/*-----------------------------------------*/

 /* ESP METAL
int pwm = 5;
int sw = 4;
*/

/* ESP MINI
int pwm = 2;
int sw = 3;
*/

/* ESP NodeMCU */
int act1 = D0;
int act2 = D3;
int act3 = D4;

int sen1 = D5;
int sen2 = D6;
int sen3 = D7;
int sen4 = D8; //PIR

int backgroud = A0; //LED DE FONDO



int sen1_val = 0; //TOUCH
int sen2_val = 0; //TOUCH
int sen3_val = 0; //TOUCH
int sen4_val = 0; //PIR

/*LED DE FONDO*/
int backgroud_stop=0; //FIN DE ENCENDIDO DE FONDO




String c = "";

String url_sensor = "";

/*---------------------------------------------------------------*/


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

    /*Actuador*/
    pinMode(act1, OUTPUT);
    pinMode(act2, OUTPUT);
    pinMode(act3, OUTPUT);
    /*Sensor*/
    pinMode(sen1, INPUT);
    pinMode(sen2, INPUT);
    pinMode(sen3, INPUT);
    pinMode(sen4, INPUT);

    /*LED DE FONDO*/
    pinMode(backgroud, OUTPUT);



    /*Actuador*/
    digitalWrite(act1, LOW);
    digitalWrite(act2, LOW);
    digitalWrite(act3, LOW);

    /*LED DE FONDO*/
    digitalWrite(backgroud, HIGH);


    lcd.begin();
    lcd.backlight();
    lcd.print("Gerardo Murillo");

}

void loop() {
  
    
    

    
    /*Actuador*/
    
    // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        USE_SERIAL.print("[HTTP-ACTUADOR] begin...\n");
        // configure traged server and url
        //http.begin("https://192.168.1.12/test.html", "7a 9c f4 db 40 d3 62 5a 6e 21 bc 5c cc 66 c8 3e a1 45 59 38"); //HTTPS
        http.begin("http://10.10.10.10/tools/get_config_actuador.php?d=f899139df5e1059396431415e770c6dd"); //HTTP md5 send

        USE_SERIAL.print("[HTTP-ACTUADOR] GET...\n");
        // start connection and send HTTP header
        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            USE_SERIAL.printf("[HTTP-ACTUADOR] GET... code: %d\n", httpCode); // Ver contenido http-request

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();
                USE_SERIAL.println(payload); // Ver contenido http-request

                 if (payload.indexOf("d=310b86e0b62b828562fc91c7be5380a992b2786a") != -1){ // sha1 recive
                  
                 
                    if (payload.indexOf("p1=0") != -1){
                      digitalWrite(act1, LOW);
                    }

                    if (payload.indexOf("p1=1") != -1){
                      digitalWrite(act1, HIGH);
                    }

                    if (payload.indexOf("p2=0") != -1){
                      digitalWrite(act2, LOW);
                    }

                    if (payload.indexOf("p2=1") != -1){
                      digitalWrite(act2, HIGH);
                    }

                    if (payload.indexOf("p3=0") != -1){
                      digitalWrite(act3, LOW);
                    }

                    if (payload.indexOf("p3=1") != -1){
                      digitalWrite(act3, HIGH);
                    }
                  

                 }
                 
            }
        } else {
            USE_SERIAL.printf("[HTTP-ACTUADOR] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }


         

        http.end();
    }

    /*SENSOR*/
    
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        USE_SERIAL.print("[HTTP-SENSOR] begin...\n");
        // configure traged server and url
        //http.begin("https://192.168.1.12/test.html", "7a 9c f4 db 40 d3 62 5a 6e 21 bc 5c cc 66 c8 3e a1 45 59 38"); //HTTPS


        sen1_val=digitalRead(sen1);
        sen2_val=digitalRead(sen2);
        sen3_val=digitalRead(sen3);
        

        url_sensor="http://10.10.10.10/tools/get_config_sensor.php?d=f899139df5e1059396431415e770c6dd";
        url_sensor+="&p1=";
        url_sensor+=sen1_val;
        url_sensor+="&p2=";
        url_sensor+=sen2_val;
        url_sensor+="&p3=";
        url_sensor+=sen3_val;
        http.begin(url_sensor); //HTTP md5 send

        USE_SERIAL.print("[HTTP-SENSOR] GET...\n");
        // start connection and send HTTP header
        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {
            // HTTP header has been send and Server response header has been handled
            USE_SERIAL.printf("[HTTP-SENSOR] GET... code: %d\n", httpCode); // Ver contenido http-request

            // file found at server
            if(httpCode == HTTP_CODE_OK) {
                String payload = http.getString();
                USE_SERIAL.println(payload); // Ver contenido http-request
                 
            }
        } else {
            USE_SERIAL.printf("[HTTP-SENSOR] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }

    
         

        http.end();
    }

    sen4_val=digitalRead(sen4);
    USE_SERIAL.print(sen4_val);
    digitalWrite(backgroud,HIGH);

    if(sen4_val>0){
      backgroud_stop=millis()+10000;
    }

    if(backgroud_stop>millis()){
      digitalWrite(backgroud,HIGH);
    }

    

    delay(1000);
}

