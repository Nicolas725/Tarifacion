#!/usr/bin/env python
# encoding: utf-8
"""
untitled.py

Created by Fernando Mercado on 2012-05-20.
Copyright (c) 2012 __MyCompanyName__. All rights reserved.
"""

import sys
import os
import urllib2
from HTMLParser import HTMLParser
import MySQLdb

opentag = ""
numero = ""
log = file("/tmp/imports-paginas-blancas.txt", 'a')

class MyHTMLParser(HTMLParser):
    
    def handle_starttag(self, tag, attrs):
        global opentag
        opentag = tag.strip()
    def handle_endtag(self, tag):
        pass
    def handle_data(self, data):
        global opentag
        if opentag == "h2":
            nombre = data.strip()
            if nombre != "":
                show(nombre)

def show(nombre):
    global numero
    print "" + nombre + ";" + numero
    log.write(nombre + ";" + numero + "\n")

def findnumber(num2):
    global numero
    numero = num2
    
    if (numero.startswith('0')):
        response = urllib2.urlopen('http://www.paginasblancas.com.ar/Telefono/' + str(num2))
    if (numero.startswith('4') or numero.startswith('5')):
        response = urllib2.urlopen('http://www.paginasblancas.com.ar/Telefono/0261' + str(num2))
    html = response.read()
    parser = MyHTMLParser()
    parser.feed(html)

def main(numeros):
    #fnums = open("numeros.txt", 'r')
    #nn = fnums.readlines()
    
    for x in numeros:
        n = x.strip()
        print n
        try:
            if (not n.startswith('15')):
                findnumber(n)
        except:
            pass
def traer_nums_marcados():
	db = MySQLdb.connect(host="localhost", user="root", passwd="qmzp1029",db="telefonos")
	nums = []
	cur = db.cursor()
	query = "SELECT diallednumber FROM tickets_outgoing"
	cur.execute(query)
	records = cur.fetchall()
	for record in records:
		cnum = record[0].strip()
		if not cnum=="None":
			if not cnum in nums:
				nums.append(cnum)
	print len(nums)
	return nums

def traer_nums_conocidos(num_marcados):
	db = MySQLdb.connect(host="localhost", user="root", passwd="qmzp1029",db="telefonos")
	nums = []
	cur = db.cursor()
	query = "SELECT numero FROM callid"
	cur.execute(query)
	records = cur.fetchall()
	for record in records:
		cnum = record[0].strip()
		if cnum in num_marcados:
			num_marcados.remove(cnum)
	return num_marcados

if __name__ == '__main__':
    #findnumber('02634492827')
    numeros =  traer_nums_marcados()
    numeros = traer_nums_conocidos(numeros)
    print len(numeros)
    main(numeros)
