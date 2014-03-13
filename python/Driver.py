#!/usr/bin/python
import sys
sys.path.append('./plugins')
import plugins
from plugins import *
import time

stopTime = 2394562060
startTime =1394562060
interval = 5
lastRun = time.time()
checkers = list()
checkers.append(FTPCheck())
checkers.append(HTTPCheck())
while True:
    
    if time.time() < startTime :
        time.sleep(1)
        continue
    if time.time() >=stopTime :
        break
    
    if interval +lastRun > time.time() :
        time.sleep(1)
        continue
      
    for c in checkers:
        c.checkServices()  

    
    
    
    lastRun = time.time()
    
    
