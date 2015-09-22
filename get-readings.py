import socket
from time import sleep
import os
import sqlite3 as lite
#######################
ipaddr = "192.168.1.73"	#### CHANGE ME TO ESP IP ADDRESS ON USER WIFI NETWORK ####
#######################
os.system("touch test.db")
con = lite.connect("test.db")
while True:
	motion = raw_input("Table Name: ")
	with con:
		cur = con.cursor()
		cmd = "DROP TABLE IF EXISTS " + str(motion)
		cur.execute(str(cmd))
		cmd = "CREATE TABLE " + str(motion) + "(x INT, y INT, z INT)"
		cur.execute(str(cmd))
	while True:
		try:
			sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
			sock.connect((ipaddr, 80))
			sockdata = ""
			sock.sendall("hello\r")
			sockdata = sock.recv(200).strip()
			temp = sockdata.splitlines(0)
			for b in range(len(temp)):
				temp1 = temp[b]
				x1 = temp1.find(",")
				y1 = temp1.find(",", x1+1)
				z1 = temp1.find(",", y1+1)
				x = temp1[:x1]
				y = temp1[x1+1:y1]
				z = temp1[y1+1:]
				print x, y, z
				with con:
					cmd = "INSERT INTO " + motion + " VALUES(?, ?, ?)"
					cur.execute(str(cmd), (int(x), int(y), int(z)))
				con.commit()
			sock.close()
		except KeyboardInterrupt:
			print "\nStopping..."
			break
	print motion + " Complete!"
	q = raw_input("Create another table? (y/n): ")
	if (q == "n"):
		print "Database saved as test.db in current directory"
		con.close()
		quit()
