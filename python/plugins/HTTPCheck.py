import httplib
from httplib import *
import ServiceCheckInterface
from ServiceCheckInterface import *
class HTTPCheck(ServiceCheckInterface):
    
    def checkServices(self):
        ServiceCheckInterface.setup(self,"HTTP","Scorebot")
        i=0

        
        for s in self.services:
            try:
                
                conn = httplib.HTTPConnection(self.targets[i],"%d" %(self.ports[i]))
                conn.request("GET","/index.php")
                res = conn.getresponse()
                data = res.read()
            
                if "CodeNinja" in data:
                    self.intact.append(1)
                    self.available.append(1)
                    print "Host %s Service %s available" %(self.targets[i],self.servicename)
                    print "Host %s Service %s intact" %(self.targets[i],self.servicename)
 
                else:
                    self.intact.append(0)  
                    self.available.append(1)
            except:
                self.available.append(0)
                self.intact.append(0)
            i+=1
            
        ServiceCheckInterface.submitResults(self)
        
        
 