# AITask
Connect.php has all the credientials for the database
EXPAToExternal has the code for getting from Data from expa with the access token of all of the EPs connected with my token
SendingMail.php has the code for retrieving the emails from the database and sending them an html email using aws ses
EXPAToExternalWithUI is a mixture of both EXPAToExternal and Sending Mail it has a UI for the user to write the content and his/her access token and the system will upload the email on the server then retrives them and send them the email with the content written from the U

SimpleEmailService, SimpleEmailServiceMessage, SimpleEmailServiceRequest the class files of the AWS SES
