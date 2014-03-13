#!/usr/bin/python
import Database
import time

from Database import *
class ServiceCheckInterface:
    def setup (self,protocol,name):
        self.db = Database()
        self.db.setup()
        self.protocol = protocol
        self.servicename = name
        cursor = self.db.query("""SELECT services.* ,assets.ip FROM scorebot2.services 
                                    JOIN assets ON assets.id=asset_id 
                                        WHERE services.name='%s' AND services.protocol = '%s' ORDER BY team""" %( name,protocol))
        result_set = cursor.fetchall()
        self.targets = list()
        self.ports = list()
        self.usernames = list()
        self.passwords = list()
        self.services = list()
        self.available = list()
        self.intact = list()
        for row in result_set:
            self.targets.append(row['ip'])
            self.usernames.append(row['username'])
            self.passwords.append(row['password'])
            self.services.append(row['id'])
            self.ports.append(row['port'])
     
    #method should iterate through all of the services, and perform availability and integrity checks on each
    #results should be stored at the appropriate index in the available and intact lists as either a 1 or 0 depending on if it was available or not
    def checkServices(self):
        pass
    
    def submitResults(self):
        i=0
        for s in self.available:
            self.db.insert("""
            INSERT INTO servicechecks (service_id,available,intact,timestamp) VALUES (%d,%d,%d,%d)""" % (self.services[i],self.available[i],self.intact[i],time.time())
            )
    
            i +=1
        self.db.close()
            
       
        
        
        
