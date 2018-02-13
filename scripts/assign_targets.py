import pymysql
import random

db = pymysql.connect(user="root", passwd="codepurple", db="gotcha", unix_socket="/run/mysqld/mysqld.sock", autocommit=True)
cursor = db.cursor()
cursor.execute("select * from users where target is NULL and admin is NULL;")
results = cursor.fetchall()

# Create user list and shuffle it (basically we're creating a linked list)
users_left = []
for i in results:
    if i[7] != 1:
        users_left.append(i[1])
random.shuffle(users_left)

# Go through list and set each person's target to be the next person in the list
for i in range(len(users_left)):
    try:
        cursor.execute("update users set target = '"+users_left[i+1]+"' where username = '"+users_left[i]+"';")
    except IndexError:
        cursor.execute("update users set target = '"+users_left[0]+"' where username = '"+users_left[i]+"';")

db.close()
