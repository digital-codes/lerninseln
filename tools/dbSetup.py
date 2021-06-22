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

import pandas as pd
import json

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
#   id, name, city, plz, country, street, streetnum, geo, email, phone, www

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
# engine = create_engine('mysql://lerninseln:lerninseln@localhost/lerninseln', echo=True)

Base = declarative_base()

# to drop the table initially,
# check if it exists via schema metadata

if DROP_ALL:
    metadata1 = MetaData()
    metadata1.reflect(bind=engine)
    for table in reversed(metadata1.sorted_tables):
        #engine.execute(table.delete()) # deletes content, but not table
        Base.metadata.drop_all(engine, [table], checkfirst=True)
    print("All tables dropped")


metadata = MetaData()
metadata.reflect(bind=engine)

########################################################################
class User(Base):
    """"""
    __tablename__ = "user"
 
    id = Column(Integer, primary_key=True)
    username = Column(String(255), nullable=False)
    firstname = Column(String(255), nullable=False)
    lastname = Column(String(255), nullable=False)
    email = Column(String(255), nullable=False)
    pwd = Column(String(255), nullable=False)

    #----------------------------------------------------------------------
    def __init__(self, username, firstname, lastname, email, pwd):
        """"""
        self.username = username
        self.firstname = firstname
        self.lastname = lastname
        self.email = email
        self.pwd = pwd

########################################################################
class Provider(Base):
    """"""
    __tablename__ = "provider"
 
    id = Column(Integer, primary_key=True)
    name = Column(String(255), nullable=False)
    country = Column(String(255))
    city = Column(String(255), nullable=False)
    citycode = Column(String(255), nullable=False)
    street = Column(String(255), nullable=False)
    streetnum = Column(String(255))
    latlon = Column(String(255)) # json array
    person = Column(String(255))
    email = Column(String(255), nullable=False)
    phone = Column(String(255))
    www = Column(String(255), nullable=False)


    #----------------------------------------------------------------------
    def __init__(self, name, country, city, citycode, street,
                 streetnum, latlon, person, email, phone, www):
        """"""
        self.name = name
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
class Category(Base):
    """"""
    __tablename__ = "category"
 
    id = Column(Integer, primary_key=True)
    name = Column(String(255), nullable=False)
    color = Column(String(255), nullable=False)
    logo = Column(String(255))
                            

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
    name = Column(String(255), nullable=False)
    email = Column(String(255), nullable=False)
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
class Event(Base):
    """"""
    __tablename__ = "event"
 
    id = Column(Integer, primary_key=True)
    title = Column(String(255), nullable=False)
    date = Column(String(255), nullable=False)
    time = Column(String(255), nullable=False)
    cost = Column(Integer, nullable=False)
    costinfo = Column(String(255))

    provider_id = Column(Integer, ForeignKey('provider.id', ondelete="CASCADE"))
    provider = relationship("Provider", back_populates="event")

    category_id = Column(Integer, ForeignKey('category.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    category = relationship("Category", back_populates="event")
                            

    #----------------------------------------------------------------------
    def __init__(self, title, date, time, cost, costinfo, provider, category):
        """"""
        self.title = title
        self.date = date
        self.time = time
        self.cost = cost
        self.costinfo = costinfo
        self.provider_id = provider
        self.category_id = category



########################################################################
class Ticket(Base):
    """"""
    __tablename__ = "ticket"
 
    id = Column(Integer, primary_key=True)
    avail = Column(Integer, nullable=False)
    reserved = Column(Integer, nullable=False)
    event_id = Column(Integer, ForeignKey('event.id', ondelete="CASCADE"))
    # next one only for sqlalch orm to get access to addr.user.<key>
    event = relationship("Event", back_populates="ticket")
                            

    #----------------------------------------------------------------------
    def __init__(self, avail, reserved, event):
        """"""
        self.addr = avail
        self.email = reserved
        self.user_id = event


# see above, only python
Event.ticket = relationship("Ticket", order_by=Ticket.id, \
    back_populates="event",cascade="all, delete, delete-orphan")

Ticket.code = relationship("Code", order_by=Code.id, \
    back_populates="ticket",cascade="all, delete, delete-orphan")

Provider.event = relationship("Event", order_by=Event.id, \
    back_populates="provider",cascade="all, delete, delete-orphan")

Category.event = relationship("Event", order_by=Event.id, \
    back_populates="category",cascade="all, delete, delete-orphan")



##############################
# create tables
Base.metadata.create_all(engine)


######### Part 2 ############
# create a Session
Session = sessionmaker(bind=engine)
session = Session()


if DROP_ALL:
    # read initial providers
    p = pd.read_csv("lernorte1.csv")
    def geo(x):
        return json.dumps({"lat":x.lat,"lon":x.lon})
    # create geo string
    p["geo"] = p.apply(geo,axis=1)

    for r in p.itertuples():
        print(r)
        provider = Provider(r.Name,"Deutschland",r.Ort,r.PLZ,
                            " ".join(r.Strasse.split(" ")[:-1]),r.Strasse.split(" ")[-1],
                            r.geo,"","","","")
        session.add(provider)
        
    session.commit()
    


    

sys.exit()


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


