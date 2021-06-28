from xml.dom import minidom
import MySQLdb
import sys

db = MySQLdb.connect(host='localhost', user='root', passwd='', db='telefonos')

xmldoc = minidom.parse(sys.argv[1])

lista = xmldoc.childNodes[0]

# primer elemento CallAccounting de la lista
elem = lista.childNodes[0]

def getEntry(node, tag):
    try:
        value = node.getElementsByTagName(tag)[0].childNodes[0].data
        return value
    except: pass

entradas = []
salidas = []
entradasTransf = []
salidasTransf = []

for x in lista.childNodes:

    entry = {}
    entry["Date"] = getEntry(x, "Date")
    entry["DialledNumber"] = getEntry(x, "DialledNumber")
    entry["CallDuration"] = getEntry(x, "CallDuration")
    entry["Time"] = getEntry(x, "Time")
    entry["ChargedUserID"] = getEntry(x, "ChargedUserID")
    entry["SubscriberName"] = getEntry(x, "SubscriberName")

    # ChargedUserType: Tipo del user ID al que se le carga la llamada
    #  A : Suscriber
    #  G : Group
    #  L : Analog trunk to public network
    #  N : ISDN Basic access to public network
    #  P : Primary access in transit/break-in/break-out cases
    #  V : Ip trunk
    #  D : DASS2 trunk
    #  B : ISDN basic
    entry["ChargedUserType"] = getEntry(x, "ChargedUserType")

    # ChargedUserID
    # Si:
    #   Outgoing: especifica quien llama
    #   Incoming: suscriptor que atiende
    #   Otros:  identificador de trunk
    entry["ChargedUserID"] = getEntry(x, "ChargedUserID")

    # Nombre del que llama o llaman depende del sentido de la llamada
    entry["SubscriberName"] = getEntry(x, "SubscriberName")

    # TrunkType
    # L : Analog trunk to public network (PSTN)
    # N : ISDN basic access to public network (PSTN)
    # P : Public or private primary access
    # V : IP Trunk
    # D : DASS2 trunk
    # B : ISDN basic access to public network (PSTN)

    # Service - bearer capability
    # ST : Servicio telefonico
    # T+ : Telefax
    # ** : no definido

    # DiallingMode
    # M : Manual
    # R : short code
    # I : individual short code

    # RingingDuration
    # Para incoming
    entry["RingingDuration"] = getEntry(x, "RingingDuration")

    # BusinessCode

    # InitialUserType
    # A : suscriptor
    # G : grupo

    # InitialUserID
    # El suscriptor llamado inicialmente (llamdas entrantes)
    # Irrelevante para llamadas salientes
    entry["InitialUserID"] = getEntry(x, "InitialUserID")

    # AdditionalServices
    # T : Online metering
    # I : User-to-user signalling
    # S : Disa transit
    # X : Transfer
    # R : external diversion
    # N:  PABX forwarding
    # P : VPN
    # A : ARS
    # O: Overflow
    # F: forced on net
    entry["AdditionalServices"] = getEntry(x, "AdditionalServices")

    entry["CommunicationType"] = getEntry(x, "CommunicationType")
    entry["TrunkID"] = getEntry(x, "TrunkID")

    tipo = entry["CommunicationType"]

    if (tipo == "Incoming"):
        entradas.append(entry)
    elif (tipo == "Outgoing"):
        salidas.append(entry)
    elif (tipo == "IncomingTransfer"):
        entradasTransf.append(entry)
    elif (tipo == "OutgoingTransfer"):
        salidasTransf.append(entry)

def  persist_outgoing(entry):
    c = db.cursor()
    query = """INSERT INTO tickets_outgoing
        (date, diallednumber, callduration, time, chargeduserid, suscribername,
        chargedusertype, ringingduration, initialuserid, communicationtype, trunkid)
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')""" % (entry["Date"], entry["DialledNumber"], entry["CallDuration"], entry["Time"], entry["ChargedUserID"], entry["SubscriberName"], entry["ChargedUserType"], entry["RingingDuration"], entry["InitialUserID"], entry["CommunicationType"], entry["TrunkID"])

    c.execute(query)

def  persist_incoming(entry):
    c = db.cursor()
    query = """INSERT INTO tickets_incoming
        (date, diallednumber, callduration, time, chargeduserid, suscribername,
        chargedusertype, ringingduration, initialuserid, communicationtype, trunkid)
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')""" % (entry["Date"], entry["DialledNumber"], entry["CallDuration"], entry["Time"], entry["ChargedUserID"], entry["SubscriberName"], entry["ChargedUserType"], entry["RingingDuration"], entry["InitialUserID"], entry["CommunicationType"], entry["TrunkID"])

    c.execute(query)

def  persist_outgoing_transfer(entry):
    c = db.cursor()
    query = """INSERT INTO tickets_outgoing_transfer
        (date, diallednumber, callduration, time, chargeduserid, suscribername,
        chargedusertype, ringingduration, initialuserid, communicationtype, trunkid)
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')""" % (entry["Date"], entry["DialledNumber"], entry["CallDuration"], entry["Time"], entry["ChargedUserID"], entry["SubscriberName"], entry["ChargedUserType"], entry["RingingDuration"], entry["InitialUserID"], entry["CommunicationType"], entry["TrunkID"])

    c.execute(query)

def  persist_incoming_transfer(entry):
    c = db.cursor()
    query = """INSERT INTO tickets_incoming_transfer
        (date, diallednumber, callduration, time, chargeduserid, suscribername,
        chargedusertype, ringingduration, initialuserid, communicationtype, trunkid)
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')""" % (entry["Date"], entry["DialledNumber"], entry["CallDuration"], entry["Time"], entry["ChargedUserID"], entry["SubscriberName"], entry["ChargedUserType"], entry["RingingDuration"], entry["InitialUserID"], entry["CommunicationType"], entry["TrunkID"])

    c.execute(query)

print ("Salidas: %d" % len(salidas))
for x in salidas:
    persist_outgoing(x)

print ("Entradas: %d" % len(entradas))
for x in entradas:
    persist_incoming(x)

print ("SalidasT: %d" % len(salidasTransf))
for x in salidasTransf:
    persist_outgoing_transfer(x)

print ("EntradasT: %d" % len(entradasTransf))
for x in entradasTransf:
    persist_incoming_transfer(x)
