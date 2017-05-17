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
        '-vlak_id w             Cislo w udavajici posledni ID vlaku v DB (nebo 0)\n'
        '-prijezd_id x             Cislo x udavajici posledni ID spoje v DB (nebo 0)\n'
        '-cislo_spoje y            Cislo y udavajici posledni cislo spoje v DB (nebo 0)\n'
        '-smer_id z             Cislo z znacici smer spoje (1=s/2=j)\n'
        'Skript ocekava vstupni soubor ve formatu vlak\\n cas odjezdu/prijezdu\\n\n'
        'Jednotlive informace jsou od sebe oddeleny presne 2mi mezerami')

def parseTrain(line, outputfile, vlak_id, prijezd_id, stanice_id, smer_id, line_cnt, cislo_spoje, spoj_id):

    num = '' #cislo vlaku
    typ = '' #typ vlaku
    name = '' #jmeno vlaku
    prijezd = '' #prijezd
    odjezd = '' #odjezd
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
    
        out = "INSERT INTO vlak VALUES (\'" + str(vlak_id) + "\',\'" + str(num) + "\',\'" + str(typ) + "\',\'" + str(name) + "\');"
        with codecs.open(outputfile, 'a', encoding='utf-8') as f:
            f.write(out+'\n')

        out = "INSERT INTO spoje VALUES (\'" + str(spoj_id) + "\',\'" + str(vlak_id) + "\',\'" + str(smer_id) + "\',\'" + str(day) + "\',\'" + str(cislo_spoje) + "\');"
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
                prijezd += ch
            
            if (word_cnt == 2):
                odjezd += ch
         

        if ((prijezd == "NULL") or (odjezd == "NULL")):
            out = "INSERT INTO prijezdy VALUES (\'" + str(prijezd_id) + "\',\'" + str(stanice_id) + "\'," + str(prijezd) + "," + str(odjezd) + ",\'" + str(cislo_spoje) + "\');"
        else:
            out = "INSERT INTO prijezdy VALUES (\'" + str(prijezd_id) + "\',\'" + str(stanice_id) + "\',\'" + str(prijezd) + "\',\'" + str(odjezd) + "\',\'" + str(cislo_spoje) + "\');"
        with codecs.open(outputfile, 'a', encoding='utf-8') as f:
            f.write(out+'\n')           
          

def main(argv):
    inputfile = ''
    outputfile = ''
    vlak_id = 0 #zacinajici id vlaku
    spoj_id = 30 #zacinajici id spoje
    prijezd_id = 0 #zacinajici id prijezdu
    stanice_id = 0 #zacinajici id stanice (po 13 iteracich =0)
    smer_id = 0 #id smeru (1=s / 2=j)
    cislo_spoje = 1 #cislo spoje (po 13 iteracich ++)
    line_cnt = 0

    if ((sys.argv[1] == "--help") or (sys.argv[1] == "-h")):
        helpMessage()
        return
    if (sys.argv[1] == "-i"):
        inputfile = sys.argv[2]
    if (sys.argv[3] == "-o"):
        outputfile = sys.argv[4]
    if (sys.argv[5] == "-vlak_id"):
        vlak_id = int(sys.argv[6])
    if (sys.argv[7] == "-prijezd_id"):
        prijezd_id = int(sys.argv[8])
    if (sys.argv[9] == "-cislo_spoje"):
        cislo_spoje = int(sys.argv[10])
    if (sys.argv[11] == "-smer_id"):
        smer_id = int(sys.argv[12])

    with codecs.open(inputfile,'r',encoding='utf8') as f:
        for line in f:
            
            if ((line_cnt > 0) and (line_cnt % 14 != 0)): #Pokud je cokoliv krome 1 a 14 line, zvysim id prijezdu
                if ((prijezd_id > 1) and (prijezd_id%13 == 0)): #Pokud uz bylo 13 stanic, stanice_id zase od 0
                    stanice_id = 0
                prijezd_id += 1 #Zvysim id prijezdu
                stanice_id += 1 #Zvysim id stanice
            if ((line_cnt > 0) and (line_cnt%14 == 0)): #Pokud je prvni nebo 15 line, zvysim id vlaku
                vlak_id += 1
                spoj_id += 1
                cislo_spoje += 1
                
            parseTrain(line, outputfile, vlak_id, prijezd_id, stanice_id, smer_id, line_cnt, cislo_spoje, spoj_id)
            line_cnt += 1
            
 
if __name__ == '__main__':
    main(sys.argv)