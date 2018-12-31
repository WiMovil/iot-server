
int act1=2;
int act2=3;
int act3=4;
int act4=5;



String read_serial="";




void setup() {


    Serial.begin(115200);
    Serial3.begin(115200);


    pinMode(act1,OUTPUT);
    pinMode(act2,OUTPUT);
    pinMode(act3,OUTPUT);
    pinMode(act4,OUTPUT);

}

void loop() {
  
  actuador();
    
}


void actuador(){

  read_serial=Serial3.readStringUntil('\n');
  Serial.println(read_serial);


  if (read_serial.indexOf("p1=255") != -1){
       digitalWrite(act1,HIGH);
       lcd.setCursor(0, 1);
       lcd.print("P1=ON");
   }
   if (read_serial.indexOf("p1=0") != -1){
       digitalWrite(act1,LOW);
       lcd.setCursor(0, 1);
       lcd.print("P1=OFF");
   }


   if (read_serial.indexOf("p2=255") != -1){
       digitalWrite(act2,HIGH);
   }
   if (read_serial.indexOf("p2=0") != -1){
       digitalWrite(act2,LOW);
   }
   

   if (read_serial.indexOf("p3=255") != -1){
       digitalWrite(act3,HIGH);
   }
   if (read_serial.indexOf("p3=0") != -1){  
       digitalWrite(act3,LOW);
   }


   if (read_serial.indexOf("p4=255") != -1){
       digitalWrite(act4,HIGH);
   }
   if (read_serial.indexOf("p4=0") != -1){
       digitalWrite(act4,LOW);
   }

   
  
}


