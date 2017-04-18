#autor: Jan Uhlíř
#mail: xuhlir16@stud.fit.vutbr.cz

import sys
import codecs

def helpMessage():
    print('Vitejte, skript v jazyce Python 3 pro prevod txt souboru na sadu SQL prikazu\n'
        '\n'
        'POUZITI:\n'
        '--help             Vytiskne napovedu, zadavejte samostatne\n'
        '-i filename       Zadany vstupni soubor ve formatu XML\n'
        '-o filename       Zadany vystupni soubor ve formatu\n'
        '-tid w             Cislo w udavajici posledni ID vlaku v DB (nebo 0)\n'
        '-pid x             Cislo x udavajici posledni ID spoje v DB (nebo 0)\n'
        '-cnum y            Cislo y udavajici posledni cislo spoje v DB (nebo 0)\n'
        '-mid z             Cislo z znacici smer spoje (1=s/2=j)\n'
        'Skript ocekava vstupni soubor ve formatu vlak\\n cas odjezdu/prijezdu\\n\n'
        'Jednotlive informace jsou od sebe oddeleny presne 2mi mezerami')

def parseTrain(line, outputfile, tid, pid, sid, mid, line_cnt, cnum):

    num = '' #cislo vlaku
    typ = '' #typ vlaku
    name = '' #jmeno vlaku
    arr = '' #prijezd
    dep = '' #odjezd
    day = '2017-03-13'
    write_p = False #Zapis prijezdy
    write_s = False #Zapis spoje
    space_cnt = 0
    word_cnt = 1

    #print(line_cnt)
    #print(line)

    if ((line_cnt == 0) or (line_cnt%14 == 0)): #prvni a kazda 14. line - vlak
        #print("vlak")
        for ch in line:
            if ((ord(ch) == 10) or (ord(ch) == 0x0D)):
                continue

            if ((ch == " ") and (word_cnt < 3)):
                space_cnt += 1
                continue

            if (space_cnt == 2):
                word_cnt +=1
                space_cnt = 0
        
            if (word_cnt == 1):
                num += ch
        
            if (word_cnt == 2):
                typ += ch
        
            if (word_cnt == 3):
                name += ch
    
        out = "INSERT INTO vlak VALUES (\'" + str(tid) + "\',\'" + str(num) + "\',\'" + str(typ) + "\',\'" + str(name) + "\');"
    
        with codecs.open(outputfile, 'a', encoding='utf-8') as f:
            f.write(out+'\n')
    
    else: #zbytek - spoje + prijezdy
        #print("zbytek")
        for ch in line:
            if ((ord(ch) == 10) or (ord(ch) == 0x0D)):
                continue
            
            if ((ch == " ") and (word_cnt < 3)):
                space_cnt += 1
                continue

            if (space_cnt == 2):
                word_cnt +=1
                space_cnt = 0
            
            if (word_cnt == 1):
                arr += ch
            
            if (word_cnt == 2):
                dep += ch
         

        if ((arr == "NULL") or (dep == "NULL")):
            out = "INSERT INTO prijezdy VALUES (\'" + str(pid) + "\',\'" + str(sid) + "\'," + str(arr) + "," + str(dep) + ",\'" + str(mid) + "\',\'" + str(cnum) + "\');"
        else:
            out = "INSERT INTO prijezdy VALUES (\'" + str(pid) + "\',\'" + str(sid) + "\',\'" + str(arr) + "\',\'" + str(dep) + "\',\'" + str(mid) + "\',\'" + str(cnum) + "\');"
        with codecs.open(outputfile, 'a', encoding='utf-8') as f:
            f.write(out+'\n')           
        
        out = "INSERT INTO spoje VALUES (\'" + str(pid) + "\',\'" + str(tid) + "\',\'" + str(pid) + "\',\'" + str(day) + "\');"
        with codecs.open(outputfile, 'a', encoding='utf-8') as f:
            f.write(out+'\n')      

def main(argv):
    inputfile = ''
    outputfile = ''
    tid = 0 #zacinajici id vlaku
    pid = 0 #zacinajici id prijezdu
    sid = 0 #zacinajici id stanice (po 13 iteracich =0)
    mid = 0 #id smeru (1=s / 2=j)
    cnum = 1 #cislo spoje (po 13 iteracich ++)
    line_cnt = 0

    if ((sys.argv[1] == "--help") or (sys.argv[1] == "-h")):
        helpMessage()
        return
    if (sys.argv[1] == "-i"):
        inputfile = sys.argv[2]
    if (sys.argv[3] == "-o"):
        outputfile = sys.argv[4]
    if (sys.argv[5] == "-tid"):
        tid = int(sys.argv[6])
    if (sys.argv[7] == "-pid"):
        pid = int(sys.argv[8])
    if (sys.argv[9] == "-cnum"):
        cnum = int(sys.argv[10])
    if (sys.argv[11] == "-mid"):
        mid = int(sys.argv[12])

    with codecs.open(inputfile,'r',encoding='utf8') as f:
        for line in f:
            
            if ((line_cnt > 0) and (line_cnt % 14 != 0)): #Pokud je cokoliv krome 1 a 14 line, zvysim id prijezdu
                if ((pid > 1) and (pid%13 == 0)): #Pokud uz bylo 13 stanic, sid zase od 0
                    sid = 0
                    cnum += 1
                pid += 1 #Zvysim id prijezdu
                sid += 1 #Zvysim id stanice
            if ((line_cnt > 0) and (line_cnt%14 == 0)): #Pokud je prvni nebo 15 line, zvysim id vlaku
                tid += 1
                
            parseTrain(line, outputfile, tid, pid, sid, mid, line_cnt, cnum)
            line_cnt += 1
            
 
if __name__ == '__main__':
    main(sys.argv)