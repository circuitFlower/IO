#include <ESP8266WiFi.h>
#include <Wire.h>
#include <EEPROM.h>

//  SDA -> ESP GPIO 0
//  SDL -> ESP GPIO 2

#define DEVICE (0x53) // Device address as specified in data sheet 

byte _buff[6];
char POWER_CTL = 0x2D;	//Power Control Register
char DATA_FORMAT = 0x31;
char DATAX0 = 0x32;	//X-Axis Data 0
char DATAX1 = 0x33;	//X-Axis Data 1
char DATAY0 = 0x34;	//Y-Axis Data 0
char DATAY1 = 0x35;	//Y-Axis Data 1
char DATAZ0 = 0x36;	//Z-Axis Data 0
char DATAZ1 = 0x37;	//Z-Axis Data 1

int np1 = 0;
int np2 = 0;
int np3 = 0;
int cnt = 0;
int x, y, z;
char ssid[40] = "";
char password[40] = "";
WiFiServer server(80);

void setup()
{
  Serial.begin(9600);
  EEPROM.begin(128);
  Wire.pins(0,2);
  Wire.begin();
  WiFi.softAP("IO");
  server.begin();
  //Put the ADXL345 into +/- 4G range by writing the value 0x01 to the DATA_FORMAT register.
  writeTo(DATA_FORMAT, 0x01);
  //Put the ADXL345 into Measurement Mode by writing 0x08 to the POWER_CTL register.
  writeTo(POWER_CTL, 0x08);
  cnt = EEPROM.read(0);
}

void loop()
{
  if (cnt == 0)
  {
    WiFiClient client = server.available();
    if (!client) {
      return;
    }
    while(!client.available()){
      delay(1);
    }
    String req = client.readStringUntil('\r');
    client.flush();
    Serial.println(req);
    np1 = (req.indexOf("/") + 1);
    if (np1 != -1)
    {
      np2 = req.indexOf("/", np1);
      for (int i = 0; i < (np2 - np1); i++)
      {
        ssid[i] = req[np1 + i];
      }
      np3 = req.indexOf(" ", np2);
      np2++;
      for (int i = 0; i < (np3 - np2); i++)
      {
        password[i] = req[np2 + i];
      }
    }
    client.flush();
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) 
    {
      delay(100);
    }
    IPAddress ip = WiFi.localIP();
    String ipStr = String(ip[0]) + '.' + String(ip[1]) + '.' + String(ip[2]) + '.' + String(ip[3]);
    String s = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n<!DOCTYPE HTML>\r\n<html>\r\n";
    s += ipStr;
    s += "<br>WiFi Network Setup Complete!</html>\n";
    client.print(s);
    cnt = 1;
    EEPROM.write(0, 1);
    EEPROM.commit();
  }
  else
  {
    server.begin();
    //delay(100);
    WiFiClient client = server.available();
    if (!client)
    {
      return;
    }
    while(!client.available())
    {
      delay(1);
    }
    String req = client.readStringUntil('\r');
    client.flush();
    x, y, z = readAccel();
    //String s = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n<!DOCTYPE HTML>\r\n<html>\r";
    String s = String (x);
    s += ",";
    s += String (y);
    s += ",";
    s += String (z);
    //s += "</html>\n";
    client.print(s);
  }
}



int readAccel()
{
  uint8_t howManyBytesToRead = 6;
  readFrom( DATAX0, howManyBytesToRead, _buff); //read the acceleration data from the ADXL345
  // each axis reading comes in 10 bit resolution, ie 2 bytes.  Least Significat Byte first!!
  // thus we are converting both bytes in to one int
  x = (((int)_buff[1]) << 8) | _buff[0];   
  y = (((int)_buff[3]) << 8) | _buff[2];
  z = (((int)_buff[5]) << 8) | _buff[4];
  return x, y, z;
}

void writeTo(byte address, byte val)
{
  Wire.beginTransmission(DEVICE); // start transmission to device 
  Wire.write(address);             // send register address
  Wire.write(val);                 // send value to write
  Wire.endTransmission();         // end transmission
}

// Reads num bytes starting from address register on device in to _buff array
void readFrom(byte address, int num, byte _buff[])
{
  Wire.beginTransmission(DEVICE); // start transmission to device 
  Wire.write(address);             // sends address to read from
  Wire.endTransmission();         // end transmission
  Wire.beginTransmission(DEVICE); // start transmission to device
  Wire.requestFrom(DEVICE, num);    // request 6 bytes from device
  int i = 0;
  while(Wire.available())         // device may send less than requested (abnormal)
  { 
    _buff[i] = Wire.read();    // receive a byte
    i++;
  }
  Wire.endTransmission();         // end transmission
}

