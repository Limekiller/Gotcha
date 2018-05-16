import pymysql
import sys
import smtplib
from urllib.parse import unquote
from email.mime.text import MIMEText


def mail(stri, emails):
    server = smtplib.SMTP('smtp.gmail.com:587')
    server.ehlo()
    server.starttls()
    server.login("gotcha.gc.my@gmail.com", "gomapleleafs")
    msg = MIMEText(stri)
    msg['Subject'] = "Gotcha Announcements"
    msg['From'] = "gotcha.gc.my@gmail.com"
    msg['Bcc'] = ", ".join(emails)
    server.sendmail("gotcha.gc.my@gmail.com", emails, msg.as_string())
    server.quit()

# Make a connection to MySQL server
db = pymysql.connect(user="root", passwd="codepurple", db="gotcha", unix_socket="/run/mysqld/mysqld.sock", autocommit=True)
cursor = db.cursor()
conflict = False


# Create a list of all admin emails 
# cursor.execute("select email from users where admin is NULL or admin != 1;")
cursor.execute("select email from users;")
results = cursor.fetchall()
emails = []
for i in results:
    emails.append(i[0])
print(emails)

mail(unquote(sys.argv[1]), emails)

db.close()
