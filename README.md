# RestAPI-With-Slim-Framework
Route	Method	Arguments	Response(Match the Response 
with Number)
/login	POST	username, latitude, longitude, channel 	Response =1
/checkUser	GET	username	Response=2
/register	POST	username, email	Response=3
/ checkEmail	GET	email	Response=4
/updateLocation	POST	username, latitude, longitude, channel	Response=5
/nOfOnline	GET	username,	Response=6


Response
1.	
a.	If Missing argument then response is 

{“status”:”fail”,“error”:{“code”:integer}}

b.	 If Unknown username then response is 

   {“status”:”fail”,“error”:{“code”:integer}}

c.	If Success then response is

 {"status":"success","data":{"secret":"EH$Gy9Ud"}}  
[secret] will be the user password.

d.	If Server Error Occurred then response is 

{“status”:”fail”,“error”:{“code”:integer} }



2.	     
a.	If Missing argument then response is 
{“status”:”fail”,“error”:{“code”:integer}}
b.	If not exist then response is  
{"status":"success","data":{"unique":true}}
c.	If Exist then response is 
 {"status":"success","data":{"unique":false}}
d.	If Server Error Occurred then response is 
{“status”:”fail”,“error”:{“code”:integer	} }
3.	  
a.	If Missing argument then response is 
{“status”:”fail”,“error”:{“code”:integer}}

b.	If username or Email already Exist then Response is 
{“status”:”fail”,“error”:{“code”:integer}}

c.	If Success then response is  {"status":"success","data":{"secret":"EH$Gy9Ud"}}  
[secret] will be the user password.

d.	If Server Error Occurred then response is {“status”:”fail”,“error”:{“code”:integer} }


4.	  Same as Response Number 2.


5.	
a.	If Missing argument then response is 
{“status”:”fail”,“error”:{“code”:integer}}

b.	 If Unknown username then response is    {“status”:”fail”,“error”:{“code”:integer}} 
c.	If Success then response is   
 {“status”:”success”}
d.	If Server Error Occurred then response is {“status”:”fail”,“error”:{“code”:integer} }


6.	  
a.	If Missing argument then response is 
{“status”:”fail”,“error”:{“code”:integer}}

b.	If Success then response is  {"status":"success","data":{"online":integer}}  

c.	If Server Error Occurred then response is {“status”:”fail”,“error”:{“code”:integer} }


Appendix 
Error Code	Explanation 
1000	Internal Server error
1001	Missing Argument
1002	User Not Exist
1003	Duplicate User name or email
