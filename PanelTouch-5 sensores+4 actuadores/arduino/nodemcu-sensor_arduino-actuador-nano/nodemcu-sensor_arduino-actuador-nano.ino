int act1=2;
int act2=3;
int act3=4;
int act4=5;

int est1=6;
int est2=7;
int est3=8;
int est4=9;



String read_serial="";



void setup() {


    Serial.begin(115200);


    pinMode(act1,OUTPUT);
    pinMode(act2,OUTPUT);
    pinMode(act3,OUTPUT);
    pinMode(act4,OUTPUT);

    pinMode(est1,OUTPUT);
    pinMode(est2,OUTPUT);
    pinMode(est3,OUTPUT);
    pinMode(est4,OUTPUT);

}

void loop() {
  
  actuador();
    
}


void actuador(){

  read_serial=Serial.readStringUntil('\n');


  if (read_serial.indexOf("p1=255") != -1){
       digitalWrite(act1,HIGH);
       digitalWrite(est1,HIGH);
   }
   if (read_serial.indexOf("p1=0") != -1){
       digitalWrite(act1,LOW);
       digitalWrite(est1,LOW);
   }


   if (read_serial.indexOf("p2=255") != -1){
       digitalWrite(act2,HIGH);
       digitalWrite(est2,HIGH);
   }
   if (read_serial.indexOf("p2=0") != -1){
       digitalWrite(act2,LOW);
       digitalWrite(est2,LOW);
   }
   

   if (read_serial.indexOf("p3=255") != -1){
       digitalWrite(act3,HIGH);
       digitalWrite(est3,HIGH);
   }
   if (read_serial.indexOf("p3=0") != -1){  
       digitalWrite(act3,LOW);
       digitalWrite(est3,LOW);
   }


   if (read_serial.indexOf("p4=255") != -1){
       digitalWrite(act4,HIGH);
       digitalWrite(est4,HIGH);
   }
   if (read_serial.indexOf("p4=0") != -1){
       digitalWrite(act4,LOW);
       digitalWrite(est4,LOW);
   }

   
  
}


