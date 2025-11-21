#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""Generate UML Diagrams for Kacchi-Ganj"""

import ssl
import urllib.request
import zlib
import base64
from pathlib import Path

def encode_plantuml(text):
    """Encode PlantUML for URL"""
    compressed = zlib.compress(text.encode('utf-8'))
    b64 = base64.b64encode(compressed).decode('ascii')
    return b64.replace('+', '-').replace('/', '_')

# Create output directory
output_dir = Path('c:\\xampp\\htdocs\\Kacchi-Ganj\\UML_Diagrams')
output_dir.mkdir(exist_ok=True)

diagrams = {
    "01_UseCase_Diagram": """@startuml UseCase
!theme plain
title Kacchi-Ganj - Use Cases

skinparam backgroundColor #FEFEFE
skinparam actor backgroundColor #FFE6CC
skinparam usecase backgroundColor #D4AF37

actor User as U
actor Admin as A

U --> (Register)
U --> (Login)
U --> (Browse Menu)
U --> (Add to Cart)
U --> (Place Order)
U --> (Message Admin)

A --> (Admin Login)
A --> (Add Product)
A --> (View Orders)
A --> (Manage Users)
A --> (Reply Messages)

(Browse Menu) .> (View Product Details) : includes
(Add to Cart) .> (Browse Menu) : extends
(Place Order) .> (Add to Cart) : extends
@enduml""",

    "02_Activity_Diagram": """@startuml Activity
!theme plain
title Kacchi-Ganj - Order Flow

skinparam backgroundColor #FEFEFE
skinparam activity backgroundColor #D4AF37

start
:User Login;
:Browse Products;
:Add to Cart;
:View Cart;

if (Cart Empty?) then (Yes)
    :Show Empty;
    backward :Browse Products;
else (No)
endif

:Checkout;
:Enter Details;
:Verify Password;

if (Valid?) then (No)
    :Error Message;
    backward :Verify Password;
else (Yes)
endif

:Place Order;
:Generate Receipt;
:Success;
stop
@enduml""",

    "03_Class_Diagram": """@startuml Class
!theme plain
title Kacchi-Ganj - Classes

skinparam backgroundColor #FEFEFE
skinparam class backgroundColor #D4AF37

class User {
    id: int
    name: varchar
    email: varchar
    password: varchar
    user_type: enum
    --
    login()
    register()
    getOrders()
}

class Product {
    id: int
    name: varchar
    price: decimal
    image: varchar
    details: text
    --
    getDetails()
    updatePrice()
}

class Cart {
    id: int
    user_id: int
    product_id: int
    quantity: int
    price: decimal
    --
    addItem()
    removeItem()
    getTotal()
}

class Order {
    id: int
    user_id: int
    total: decimal
    address: text
    placed_on: date
    status: varchar
    --
    place()
    updateStatus()
}

class Message {
    id: int
    sender_id: int
    receiver_id: int
    message: text
    timestamp: datetime
    --
    send()
    read()
}

User "1" --> "*" Cart
User "1" --> "*" Order
User "1" --> "*" Message
Product "1" --> "*" Cart
@enduml""",

    "04_Sequence_Diagram": """@startuml Sequence
!theme plain
title Kacchi-Ganj - Sequence

participant User
participant Browser
participant Server
participant Database

User -> Browser: Login
Browser -> Server: authenticate()
Server -> Database: SELECT user
Database --> Server: user data
Server --> Browser: session OK
Browser --> User: home page

User -> Browser: View menu
Browser -> Server: getProducts()
Server -> Database: SELECT products
Database --> Server: products
Server --> Browser: show menu

User -> Browser: Add to cart
Browser -> Server: addToCart()
Server -> Database: INSERT cart
Database --> Server: OK
Server --> Browser: added

User -> Browser: Checkout
Browser -> Server: placeOrder()
Server -> Database: INSERT order
Database --> Server: order_id
Server --> Browser: receipt
@enduml"""
}

# SSL bypass context
ssl_context = ssl.create_default_context()
ssl_context.check_hostname = False
ssl_context.verify_mode = ssl.CERT_NONE

print("Starting diagram generation...")

for name, content in diagrams.items():
    uml_file = output_dir / f"{name}.puml"
    png_file = output_dir / f"{name}.png"
    
    # Save PlantUML source
    with open(uml_file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Created: {name}.puml")
    
    # Generate PNG
    try:
        encoded = encode_plantuml(content)
        url = f"https://www.plantuml.com/plantuml/png/{encoded}"
        
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        response = urllib.request.urlopen(req, context=ssl_context, timeout=15)
        png_data = response.read()
        
        with open(png_file, 'wb') as f:
            f.write(png_data)
        print(f"Generated: {name}.png ({len(png_data)} bytes)")
        
    except Exception as e:
        print(f"PNG generation failed for {name}: {str(e)[:50]}")

print(f"\nDone! Files saved in {output_dir}")
