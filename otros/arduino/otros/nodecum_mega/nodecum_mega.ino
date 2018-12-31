/*
  SerialPassthrough sketch

  Some boards, like the Arduino 101, the MKR1000, Zero, or the Micro, have one
  hardware serial port attached to Digital pins 0-1, and a separate USB serial
  port attached to the IDE Serial Monitor. This means that the "serial
  passthrough" which is possible with the Arduino UNO (commonly used to interact
  with devices/shields that require configuration via serial AT commands) will
  not work by default.

  This sketch allows you to emulate the serial passthrough behaviour. Any text
  you type in the IDE Serial monitor will be written out to the serial port on
  Digital pins 0 and 1, and vice-versa.

  On the 101, MKR1000, Zero, and Micro, "Serial" refers to the USB Serial port
  attached to the Serial Monitor, and "Serial1" refers to the hardware serial
  port attached to pins 0 and 1. This sketch will emulate Serial passthrough
  using those two Serial ports on the boards mentioned above, but you can change
  these names to connect any two serial ports on a board that has multiple ports.

  created 23 May 2016
  by Erik Nyquist
*/
String recive_from_nodemcu = "";
/*PUERTOS ACTUADOR*/
int act1 = 2;
int act2 = 3;
int act3 = 4;
int act4 = 5;

/*PUERTOS SENSOR*/
int sen1 = 6;
int sen2 = 7;
int sen3 = 8;
int sen4 = 9;

/*ESTADO TOUCH*/
int sen1_val = 0;
int sen2_val = 0;
int sen3_val = 0;
int sen4_val = 0;

String url_sensor = "";

void setup() {
  Serial.begin(115200);
  Serial3.begin(115200);


  pinMode(act1, OUTPUT);
  pinMode(act2, OUTPUT);
  pinMode(act3, OUTPUT);
  pinMode(act4, OUTPUT);

  
}

void actuador(){
 
  
   if (recive_from_nodemcu.indexOf("p1=0") != -1){
        digitalWrite(act1, LOW);
      }

      if (recive_from_nodemcu.indexOf("p1=1") != -1){
        digitalWrite(act1, HIGH);
      }

      if (recive_from_nodemcu.indexOf("p2=0") != -1){
        digitalWrite(act2, LOW);
      }

      if (recive_from_nodemcu.indexOf("p2=1") != -1){
        digitalWrite(act2, HIGH);
      }

      if (recive_from_nodemcu.indexOf("p3=0") != -1){
        digitalWrite(act3, LOW);
      }

      if (recive_from_nodemcu.indexOf("p3=1") != -1){
        digitalWrite(act3, HIGH);
      }

      if (recive_from_nodemcu.indexOf("p4=0") != -1){
        digitalWrite(act4, LOW);
      }

      if (recive_from_nodemcu.indexOf("p4=1") != -1){
        digitalWrite(act4, HIGH);
      }
}


void sensor(){

    sen1_val=digitalRead(sen1);
    sen2_val=digitalRead(sen2);
    sen3_val=digitalRead(sen3);
    sen4_val=digitalRead(sen4);

    url_sensor="&p1=";
    url_sensor+=sen1_val;
    url_sensor+="&p2=";
    url_sensor+=sen2_val;
    url_sensor+="&p3=";
    url_sensor+=sen3_val;
    url_sensor+="&p4=";
    url_sensor+=sen4_val;

    //Send SERIAL to NodeMCU
    Serial3.println(url_sensor); 
    
   
  
}


void loop() {

    Serial3.println("g_act");
    recive_from_nodemcu=Serial3.readStringUntil('\n');
    actuador();/*SENSOR*/


    Serial3.println("s_sen");
    sensor();/*SENSOR*/
    

   
   
   


}


