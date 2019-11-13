import MySQLdb


db = MySQLdb.connect(host='localhost', user='root', passwd='pass', db='telefonos')


file = open('archivo-callerid', 'r')

lineas = file.readlines()


def  persist_code(cod, descr):
    c = db.cursor()
    query = """INSERT INTO callid 
        (numero, descr)
        VALUES ('%s', '%s')""" % (cod, descr)
    
    c.execute(query)


for x in lineas:
    info = x.split(';')
    print "Insertando.. " + info[0] + " " + info[1]
    persist_code( info[1], info[0] )

