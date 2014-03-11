#!/usr/bin/python

import MySQLdb
class Database:
    def setup(self):
        # Open database connection
        self.db = MySQLdb.connect("localhost","root","","scorebot2" )
        
    def query(self,querystr):

        # prepare a cursor object using cursor() method
        cursor = self.db.cursor(MySQLdb.cursors.DictCursor)
        
        # execute SQL query using execute() method.
        cursor.execute(querystr)
        
        return cursor
        
    def insert(self,querystr):
        try:
  
            cursor = self.db.cursor()
            cursor.execute(querystr)         
            self.db.commit()
        except:

            self.db.rollback()
    def close(self): 
        # disconnect from server
        self.db.close()

