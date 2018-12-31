#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
ESP8266WiFiMulti WiFiMulti;


/*-----------------------------------------*/

/* ESP NodeMCU */
int sen1 = D5;
int sen2 = D6;
int sen3 = D7;
int sen4 = D8;
int sen5 = A0;



int sen1_val = 0; //TOUCH
int sen2_val = 0; //TOUCH
int sen3_val = 0; //TOUCH
int sen4_val = 0; //TOUCH
int sen5_val = 0; //Potenciometro



String url_sensor = "";


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


    /*Sensor*/
    pinMode(sen1, INPUT);
    pinMode(sen2, INPUT);
    pinMode(sen3, INPUT);
    pinMode(sen4, INPUT);
    pinMode(sen5, INPUT);

    Serial.println();
    Serial.println("STARTED");

}

void loop() {
 
  sensor();
  actuador();

    
}


void sensor(){
  
  if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        if(digitalRead(sen1)==1){
          sen1_val=255;
        }else{
          sen1_val=0;
        }

        if(digitalRead(sen2)==1){
          sen2_val=255;
        }else{
          sen2_val=0;
        }

        if(digitalRead(sen3)==1){
          sen3_val=255;
        }else{
          sen3_val=0;
        }

        if(digitalRead(sen4)==1){
          sen4_val=255;
        }else{
          sen4_val=0;
        }

        sen5_val=analogRead(sen5);

        

        url_sensor="http://10.10.10.10/tools/get_config_sensor.php?d=f899139df5e1059396431415e770c6dd";
        url_sensor+="&p1=";
        url_sensor+=sen1_val;
        url_sensor+="&p2=";
        url_sensor+=sen2_val;
        url_sensor+="&p3=";
        url_sensor+=sen3_val;
        url_sensor+="&p4=";
        url_sensor+=sen4_val;
        url_sensor+="&p5=";
        url_sensor+=sen5_val;
        http.begin(url_sensor); //HTTP md5 send

        // start connection and send HTTP header
        int httpCode = http.GET();

        // httpCode will be negative on error
        if(httpCode > 0) {


        } else {
            Serial.printf("[NODEMCU-HTTP-SENSOR] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }


        http.end();
    }

    delay(200);
}



void actuador(){

   // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {

        HTTPClient http;

        Serial.print("[HTTP-ACTUADOR] begin...\n");

        http.begin("http://10.10.10.10/tools/get_config_actuador.php?d=f899139df5e1059396431415e770c6dd"); //HTTP md5 send

        Serial.print("[HTTP-ACTUADOR] GET...\n");

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

    delay(200);
}

