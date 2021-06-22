# from https://pythonspot.com/orm-with-sqlalchemy/
# https://www.fullstackpython.com/sqlalchemy.html
# https://docs.sqlalchemy.org/en/14/orm/

from sqlalchemy import create_engine, ForeignKey
from sqlalchemy import Column, Date, Integer, String

from sqlalchemy.schema import Table, MetaData
from sqlalchemy.schema import DropTable, DropConstraint

from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import relationship, backref
from sqlalchemy.orm import sessionmaker

import datetime
import sys

######### Notes #############
##Users familiar with the syntax of CREATE TABLE may notice
##that the VARCHAR columns were generated without a length;
##on SQLite and PostgreSQL, this is a valid datatype,
##but on others, it’s not allowed. So if running this tutorial
##on one of those databases, and you wish to use SQLAlchemy to
##issue CREATE TABLE, a “length” may be provided to the String type as below:
##Column(String(50))

## Tables ##
# providers
#   id, city, plz, country, street, streetnum, geo, email, phone, www

# categories
#   id, name, logo (base64 img)

# events
#   id, title,date,time,cost (normally 0), category, provider id, category id

# tickets
#   id, avail, reserved, event id

# codes
#   id, name, email, count, ticket id
#   all fields => hash => qrcode => base64

# users
#   name, email, pwd (encrypted)

TABLES = [
    "providers",
    "categories",
    "events",
    "tickets",
    "codes",
    "users"
    ]

# initialize
DROP_ALL = True

######### Part 1 ############

engine = create_engine('sqlite:///lerninseln.db', echo=True)
Base = declarative_base()

# to drop the table initially,
# check if it exists via schema metadata
metadata = MetaData()
metadata.reflect(bind=engine)

if DROP_ALL:
    for table in reversed(metadata.sorted_tables):
        engine.execute(table.delete())
    print("All tables dropped")


########################################################################
class Provider(Base):
    """"""
    __tablename__ = "provider"
 
    id = Column(Integer, primary_key=True)
    country = Column(String)
    city = Column(String, nullable=False)
    citycode = Column(String, nullable=False)
    street = Column(String, nullable=False)
    streetnum = Column(String)
    latlon = Column(String) # json array
    person = Column(String)
    email = Column(String, nullable=False)
    phone = Column(String)
    www = Column(String, nullable=False)


    #----------------------------------------------------------------------
    def __init__(self, country, city, citycode, street,
                 streetnum, latlon, person, email, phone, www):
        """"""
        self.countr = country
        self.city = city
        self.citycode = citycode
        self.street = street
        self.streetnum = streetnum
        self.latlon = latlon
        self.person = person
        self.email = email
        self.phone = phone
        self.www = www


########################################################################
class User(Base):
    """"""
    __tablename__ = "user"
 
    id = Column(Integer, primary_key=True)
    username = Column(String, nullable=False)
    firstname = Column(String, nullable=False)
    lastname = Column(String, nullable=False)
    email = Column(String, nullable=False)
    pwd = Column(String, nullable=False)

    #----------------------------------------------------------------------
    def __init__(self, username, firstname, lastname, email, pwd):
        """"""
        self.username = username
        self.firstname = firstname
        self.lastname = lastname
        self.email = email
        self.pwd = pwd

########################################################################
class Category(Base):
    """"""
    __tablename__ = "category"
 
    id = Column(Integer, primary_key=True)
    name = Column(String, nullable=False)
    color = Column(String, nullable=False)
    logo = Column(String)
                            

    #----------------------------------------------------------------------
    def __init__(self, name,color, logo):
        """"""
        self.name = name
        self.color = color
        self.logo = logo

########################################################################
class Code(Base):
    """"""
    __tablename__ = "code"
 
    id = Column(Integer, primary_key=True)
    name = Column(String, nullable=False)
    email = Column(String, nullable=False)
    count = Column(Integer, nullable=False)
    ticket_id = Column(Integer, ForeignKey('ticket.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    ticket = relationship("Ticket", back_populates="code")
                            

    #----------------------------------------------------------------------
    def __init__(self, name, email, count, ticket):
        """"""
        self.name = name
        self.email = email
        self.count = count
        self.ticket_id = ticket


########################################################################
class Addr(Base):
    """"""
    __tablename__ = "addr"
 
    id = Column(Integer, primary_key=True)
    addr = Column(String)
    email = Column(String, nullable=False)
    user_id = Column(Integer, ForeignKey('user.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    user = relationship("User", back_populates="addr")
                            

    #----------------------------------------------------------------------
    def __init__(self, addr, email, user):
        """"""
        self.addr = addr
        self.email = email
        self.user_id = user

########################################################################
class Addr(Base):
    """"""
    __tablename__ = "addr"
 
    id = Column(Integer, primary_key=True)
    addr = Column(String)
    email = Column(String, nullable=False)
    user_id = Column(Integer, ForeignKey('user.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    user = relationship("User", back_populates="addr")
                            

    #----------------------------------------------------------------------
    def __init__(self, addr, email, user):
        """"""
        self.addr = addr
        self.email = email
        self.user_id = user

########################################################################
class Addr(Base):
    """"""
    __tablename__ = "addr"
 
    id = Column(Integer, primary_key=True)
    addr = Column(String)
    email = Column(String, nullable=False)
    user_id = Column(Integer, ForeignKey('user.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    user = relationship("User", back_populates="addr")
                            

    #----------------------------------------------------------------------
    def __init__(self, addr, email, user):
        """"""
        self.addr = addr
        self.email = email
        self.user_id = user

# see above, only python
User.addr = relationship("Addr", order_by=Addr.id, \
    back_populates="user",cascade="all, delete, delete-orphan")

# create tables
Base.metadata.create_all(engine)


######### Part 2 ############
# create a Session
Session = sessionmaker(bind=engine)
session = Session()

# Create objects  
user = User("james","James","Boogie","MIT")
session.add(user)
# need to commit in order to update id !
session.commit()
print("Created user: ",user.id)

addr = Addr("address","123@abc",user.id)
session.add(addr)


user = User("lara","Lara","Miami","UU")
session.add(user)

user = User("eric","Eric","York","Stanford")
session.add(user)

# commit the record the database
session.commit()


######### Part 3 ############

# Read objects  
for u in session.query(User).order_by(User.id):
    print (u.firstname, u.lastname)

for a in session.query(Addr).order_by(Addr.id):
    print (a.addr, a.email, a.user_id)
    # optional, see above
    # print (a.user.username)

######### Part 4 ############

# Select objects  
for u in session.query(User).filter(User.firstname == 'Eric'):
    print (u.firstname, u.lastname)
    session.delete(u)

######### Part 5 ############
# Delete objects with cascade. should delete addr too
for u in session.query(User).filter(User.id == 1):
    print (u.firstname, u.lastname)
    session.delete(u)

# not sure if needed ...
#session.commit()

for a in session.query(Addr).order_by(Addr.id):
    print (a.addr, a.email, a.user_id)


