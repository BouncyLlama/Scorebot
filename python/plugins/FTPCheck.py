
import ServiceCheckInterface
from ServiceCheckInterface import *
class FTPCheck(ServiceCheckInterface):
    
    def checkServices(self):
        i=0
        for s in self.services:
            ftp = FTP()
            try:
                
                ftp.connect(self.targets[i],"%d" %(self.ports[i]))
                self.available.append(1)
                print "Host %s Service %s available" %(self.targets[i],self.servicename)
            except IOError as e:
                self.available.append(0)
                self.intact.append(0)
                continue
            
            
            try:
                ftp.login(self.usernames[i],self.passwords[i])
                self.intact.append(1)
                print "Host %s Service %s intact" %(self.targets[i],self.servicename)
                ftp.quit()
            except error_perm as e:
                self.intact.append(0)
                ftp.quit()
                
            i+=1
        
        
 