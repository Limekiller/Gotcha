import pymysql
import smtplib
from email.mime.text import MIMEText

# Make a connection to MySQL server
db = pymysql.connect(user="root", passwd="codepurple", db="gotcha", unix_socket="/run/mysqld/mysqld.sock", autocommit=True)
cursor = db.cursor()
conflict = False


# Create a list of all admin emails 
cursor.execute("select email from users where admin = 1;")
results = cursor.fetchall()
admin_emails = []
for i in results:
    admin_emails.append(i[0])


msg = "There are some conflicting reports:\n"
cursor.execute("select * from reports;")
results = cursor.fetchall()

kill = []
killed = []
disputes = []
verified_kills = []


# Go through all reports and separate them into a list of "I have gotten somebody" reports and "Somebody got me" reports
# Save in lists as a tuple: (reporter, subject of report)
for i in results:
    if i[0] not in kill and i[0] not in killed:
        if i[2] == "kill":
            kill.append((i[0], i[1]))
        elif i[2] == "killed":
            killed.append((i[0], i[1]))
        else:
            conflict = True;
            disputes.append((i[0],i[1]));
            msg += i[0]+" has reported a dispute against "+i[1]+". Comments:\n"+i[3]+"\nFurther investigation is suggested.\n\n"


# go through kill list and verify if the opposite report has been filed in the killed list 
# add to verified list and delete reports if so; if not, check if the report is over a day old and notify the admins if so.
for i in killed:
    verified_kills.append(i)
    cursor.execute("delete from reports where filed_by = '"+i[0]+"' and against = '"+i[1]+"' and type = 'killed';")
    if (i[1], i[0]) in kill:
        cursor.execute("delete from reports where filed_by = '"+i[1]+"' and against = '"+i[0]+"' and type = 'kill';")
        kill.remove((i[1], i[0]))

for i in kill:
    cursor.execute("select * from reports where filed_by = '"+i[0]+"' and against = '"+i[1]+"' and type = 'kill' and time < date_sub(now(), interval 3 hour);")
    results = cursor.fetchall()
    if results:
        for j in results:
            if (j[1], j[0]) not in disputes:
                verified_kills.append((j[1], j[0]))
                cursor.execute("delete from reports where filed_by = '"+j[0]+"' and against = '"+j[1]+"' and type = 'kill';")


# For everybody in verified list, check if the killer actually had the victim as their target, and if so, give the killer the victim's target;
# If not, (e.g., a self defense kill) make the killer the target of whoever had the victim
for i in verified_kills:
    cursor.execute("select * from users where username  = '"+i[1]+"' and target = '"+i[0]+"';")
    results = cursor.fetchall()
    if results:
        cursor.execute("select target from users where username = '"+i[0]+"';")
        new_target = cursor.fetchall() 
        cursor.execute("UPDATE users SET target ='"+new_target[0][0]+"' where username = '"+i[1]+"';")
    else:
        cursor.execute("update users set target = '"+i[1]+"' where target = '"+i[0]+"';")
    cursor.execute("UPDATE users SET target = 'killed' where username = '"+i[0]+"';")


# Create gmail connection and send mail 
if conflict:
    server = smtplib.SMTP('smtp.gmail.com:587')
    server.ehlo()
    server.starttls()
    server.login("gotcha.gc.my@gmail.com", "gomapleleafs")
    msg = MIMEText(msg)
    msg['Subject'] = "Daily Report:"
    msg['From'] = "gotcha.gc.my@gmail.com"
    msg['To'] = ", ".join(admin_emails)
    server.sendmail("gotcha.gc.my@gmail.com", admin_emails, msg.as_string())
    server.quit()


db.close()
