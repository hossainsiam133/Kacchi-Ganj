#!/usr/bin/env python3
"""
Direct PNG generation for UML Diagrams using Pillow and custom rendering
OR via online service with proper error handling
"""

import sys
from pathlib import Path
import urllib.parse
import urllib.request
import zlib
import base64

def encode_plantuml(text):
    """Encode PlantUML text for URL"""
    compressed = zlib.compress(text.encode('utf-8'))
    b64 = base64.b64encode(compressed).decode('ascii')
    # Replace URL-unsafe characters
    b64 = b64.replace('+', '-').replace('/', '_')
    return b64

output_dir = Path('c:\\xampp\\htdocs\\Kacchi-Ganj\\UML_Diagrams')
output_dir.mkdir(exist_ok=True)

diagrams = {
    "01_UseCase_Diagram": """@startuml UseCase
!theme plain
title Kacchi-Ganj Food Ordering System - Use Cases

skinparam backgroundColor #FEFEFE
skinparam actor {
    backgroundColor #FFE6CC
    borderColor #D4AF37
}
skinparam usecase {
    backgroundColor #D4AF37
    borderColor #8B6914
    fontColor #fff
}

actor User as U
actor Admin as A

U --> (Register)
U --> (Login)
U --> (Browse Menu)
U --> (Add to Cart)
U --> (Checkout)
U --> (Place Order)
U --> (View Order Status)
U --> (Contact Admin)

A --> (Login as Admin)
A --> (Add Product)
A --> (Edit Product)
A --> (Delete Product)
A --> (View All Orders)
A --> (Manage Users)
A --> (Respond to Messages)

(Browse Menu) .> (View Product Details) : includes
(Add to Cart) .> (Browse Menu) : extends
(Checkout) .> (Add to Cart) : extends
(Place Order) .> (Checkout) : extends
@enduml""",

    "02_Activity_Diagram": """@startuml Activity
!theme plain
title Kacchi-Ganj - Order Placement Activity Flow

skinparam backgroundColor #FEFEFE
skinparam activity {
    backgroundColor #D4AF37
    borderColor #8B6914
}

start
:User Logs In;
:Browse Menu;
:Add to Cart;
:Review Cart;

if (Cart Empty?) then (Yes)
    :Show Empty Message;
    backward :Browse Menu;
else (No)
endif

:Proceed to Checkout;
:Enter Address;
:Select Payment;
:Enter Password;

if (Valid?) then (No)
    :Show Error;
    backward :Enter Password;
else (Yes)
endif

:Place Order;
:Generate Receipt;
:Clear Cart;
:Show Success;
stop
@enduml""",

    "03_Class_Diagram": """@startuml Class
!theme plain
title Kacchi-Ganj - Database Classes

skinparam backgroundColor #FEFEFE
skinparam class {
    backgroundColor #D4AF37
    borderColor #8B6914
}

class User {
    - id: int
    - name: varchar
    - email: varchar
    - password: varchar
    - user_type: enum
    --
    + login()
    + register()
    + getOrders()
}

class Product {
    - id: int
    - name: varchar
    - price: decimal
    - image: varchar
    - details: text
    --
    + getDetails()
    + updatePrice()
}

class Cart {
    - id: int
    - user_id: int
    - product_id: int
    - quantity: int
    - price: decimal
    --
    + addItem()
    + removeItem()
    + calculateTotal()
}

class Order {
    - id: int
    - user_id: int
    - total: decimal
    - address: text
    - placed_on: date
    - status: varchar
    --
    + placeOrder()
    + updateStatus()
}

class Message {
    - id: int
    - sender_id: int
    - receiver_id: int
    - message: text
    - timestamp: datetime
    --
    + sendMessage()
    + getConversation()
}

User "1" --> "*" Cart
User "1" --> "*" Order
User "1" --> "*" Message
Product "1" --> "*" Cart
@enduml""",

    "04_Sequence_Diagram": """@startuml Sequence
!theme plain
title Kacchi-Ganj - Order & Messaging Sequence

participant User
participant Browser
participant Server
participant Database

User -> Browser: Login
Browser -> Server: POST login
Server -> Database: SELECT user
Database --> Server: User data
Server --> Browser: Session OK
Browser --> User: Home page

User -> Browser: Browse menu
Browser -> Server: GET products
Server -> Database: SELECT products
Database --> Server: Product list
Server --> Browser: Display menu

User -> Browser: Add to cart
Browser -> Server: POST cart
Server -> Database: INSERT cart
Database --> Server: Success
Server --> Browser: Item added

User -> Browser: Checkout
Browser -> Server: POST order
Server -> Database: INSERT order
Database --> Server: Created
Server --> Browser: Confirmation

@enduml"""
}

print("🎨 Generating UML Diagrams...\n")

for name, content in diagrams.items():
    uml_file = output_dir / f"{name}.puml"
    png_file = output_dir / f"{name}.png"
    
    # Save PlantUML source
    with open(uml_file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"✓ {name}.puml created")
    
    # Try to generate PNG via PlantUML online service
    try:
        encoded = encode_plantuml(content)
        url = f"https://www.plantuml.com/plantuml/png/{encoded}"
        
        req = urllib.request.Request(url, headers={
            'User-Agent': 'Mozilla/5.0'
        })
        
        response = urllib.request.urlopen(req, timeout=15)
        with open(png_file, 'wb') as f:
            f.write(response.read())
        print(f"  ✓ {name}.png generated!\n")
        
    except Exception as e:
        print(f"  ⚠️  Could not generate PNG: {str(e)[:60]}")
        print(f"  → Manual option: Copy {name}.puml content to https://www.plantuml.com/plantuml/uml/\n")

print(f"\n✅ Files saved in: {output_dir}")
print(f"   - PlantUML source files (.puml)")
print(f"   - PNG diagrams (if generation succeeded)")
