#!/usr/bin/env python3
"""
Generate UML Diagrams for Kacchi-Ganj Food Ordering System
Creates: Use Case, Activity, Class, and Sequence Diagrams (PNG Format)
"""

import subprocess
import os
import sys
from pathlib import Path
import zlib
import base64

def plantuml_encode(text):
    """Encode PlantUML diagram for URL"""
    compressed = zlib.compress(text.encode('utf-8'))
    return base64.b64encode(compressed).decode('utf-8').rstrip('=')

def generate_with_plantuml_jar(uml_content, output_file):
    """Try to use local PlantUML JAR if available"""
    try:
        import tempfile
        with tempfile.NamedTemporaryFile(mode='w', suffix='.puml', delete=False) as f:
            f.write(uml_content)
            temp_file = f.name
        
        # Try common PlantUML jar locations
        jar_paths = [
            'plantuml.jar',
            'C:\\plantuml\\plantuml.jar',
            '%APPDATA%\\plantuml\\plantuml.jar'
        ]
        
        for jar_path in jar_paths:
            if os.path.exists(jar_path) or os.path.exists(os.path.expandvars(jar_path)):
                cmd = f'java -jar {jar_path} -png -o {output_file.parent} {temp_file}'
                subprocess.run(cmd, shell=True, capture_output=True)
                os.remove(temp_file)
                return True
    except:
        pass
    return False

# Define the output directory
output_dir = Path(__file__).parent / "UML_Diagrams"
output_dir.mkdir(exist_ok=True)

# ============================================================================
# 1. USE CASE DIAGRAM
# ============================================================================
use_case_diagram = """@startuml UseCase
!theme plain
title Kacchi-Ganj Food Ordering System - Use Cases

skinparam backgroundColor #FEFEFE
skinparam actor {
    backgroundColor #FFE6CC
    borderColor #D4AF37
    fontColor #000
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
A --> (View Dashboard)

(Browse Menu) .> (View Product Details) : includes
(Add to Cart) .> (Browse Menu) : extends
(Checkout) .> (Add to Cart) : extends
(Place Order) .> (Checkout) : extends
(Add Product) .> (Login as Admin) : requires
(View All Orders) .> (Login as Admin) : requires

@enduml
"""

# ============================================================================
# 2. ACTIVITY DIAGRAM
# ============================================================================
activity_diagram = """@startuml Activity
!theme plain
title Kacchi-Ganj - Order Placement Activity Flow

skinparam backgroundColor #FEFEFE
skinparam activity {
    backgroundColor #D4AF37
    borderColor #8B6914
    fontColor #000
}

start
:User Logs In to Account;
:Browse Food Menu;
:View Product Details;
:Add Desired Products to Cart;
:Review Cart Items;

if (Cart Items Present?) then (No)
    :Display Empty Cart Message;
    :Suggest Products;
    backward :Browse Food Menu;
else (Yes)
    :Proceed to Checkout;
    :Fill Delivery Information;
    :Enter Address Details;
endif

:Select Payment Method;
:Enter Verification Password;

if (Password Correct?) then (No)
    :Show Error Message;
    :Request Password Re-entry;
    backward :Enter Verification Password;
else (Yes)
    :Calculate Total Price;
    :Add Delivery Charges;
    :Generate Order;
endif

:Place Order Successfully;
:Generate Receipt PDF;
:Clear Shopping Cart;
:Send Order Confirmation;
:Display Order Summary;
:Redirect to Home;

stop

@enduml
"""

# ============================================================================
# 3. CLASS DIAGRAM
# ============================================================================
class_diagram = """@startuml Class
!theme plain
title Kacchi-Ganj - Database Class Structure

skinparam backgroundColor #FEFEFE
skinparam class {
    backgroundColor #D4AF37
    borderColor #8B6914
    fontColor #000
    arrowColor #000
}

class User {
    - id: int (PK)
    - name: varchar(255)
    - email: varchar(255)
    - password: varchar(255)
    - user_type: enum('user','admin')
    - created_at: timestamp
    ==
    + register(): void
    + login(): boolean
    + updateProfile(): void
    + getOrders(): Order[]
}

class Product {
    - id: int (PK)
    - name: varchar(255)
    - price: decimal(10,2)
    - image: varchar(255)
    - product_details: text
    - admin_id: int (FK)
    ==
    + getDetails(): string
    + updatePrice(decimal): void
    + deleteProduct(): void
}

class Cart {
    - id: int (PK)
    - user_id: int (FK)
    - pid: int (FK)
    - name: varchar(255)
    - price: decimal(10,2)
    - quantity: int
    - image: varchar(255)
    ==
    + addItem(): void
    + removeItem(): void
    + updateQuantity(): void
    + calculateTotal(): decimal
    + getCartItems(): Cart[]
}

class Order {
    - id: int (PK)
    - user_id: int (FK)
    - name: varchar(255)
    - email: varchar(255)
    - phone: varchar(20)
    - address: text
    - payment_method: varchar(50)
    - total_price: decimal(10,2)
    - product_list: text
    - placed_on: date
    - status: varchar(50)
    ==
    + placeOrder(): boolean
    + updateStatus(): void
    + generateReceipt(): string
    + getOrderDetails(): Order
}

class Message {
    - id: int (PK)
    - sender_id: int (FK)
    - receiver_id: int (FK)
    - message: text
    - timestamp: datetime
    - is_read: boolean
    ==
    + sendMessage(): boolean
    + markAsRead(): void
    + getConversation(): Message[]
    + deleteMessage(): void
}

User "1" --|> "*" Cart : has
User "1" --|> "*" Order : places
User "1" --|> "*" Message : sends
Product "1" --|> "*" Cart : added_in
Order "*" --|> "1" User : owned_by
Message "*" --|> "1" User : from_user
Message "*" --|> "1" User : to_admin

@enduml
"""

# ============================================================================
# 4. SEQUENCE DIAGRAM
# ============================================================================
sequence_diagram = """@startuml Sequence
!theme plain
title Kacchi-Ganj - User Order & Messaging Sequence

participant User
participant Browser
participant WebServer
participant Database
participant AdminPanel

User -> Browser: Login with credentials
Browser -> WebServer: POST login request
WebServer -> Database: SELECT user WHERE email=?
Database --> WebServer: User data
WebServer --> Browser: Session created, redirect to home
Browser --> User: Display home page

User -> Browser: Browse menu products
Browser -> WebServer: GET menu.php
WebServer -> Database: SELECT * FROM products
Database --> WebServer: Product list
WebServer --> Browser: Display menu with products
Browser --> User: Show all items

User -> Browser: Add product to cart
Browser -> WebServer: POST add_to_cart (product_id, qty)
WebServer -> Database: INSERT INTO cart
Database --> WebServer: Success
WebServer --> Browser: Item added message
Browser --> User: Show confirmation

User -> Browser: View and update cart
Browser -> WebServer: GET cart.php
WebServer -> Database: SELECT * FROM cart WHERE user_id=?
Database --> WebServer: Cart items
WebServer --> Browser: Display cart with update options

User -> Browser: Proceed to checkout
Browser -> WebServer: POST checkout form
WebServer -> Database: SELECT * FROM users WHERE id=?
Database --> WebServer: User info
WebServer -> WebServer: Verify password
alt Password Valid
    WebServer -> Database: INSERT INTO order
    Database --> WebServer: Order created
    WebServer -> Database: DELETE FROM cart
    Database --> WebServer: Cart cleared
    WebServer -> Browser: Order confirmation + receipt
    Browser --> User: Show success & receipt
else Password Invalid
    WebServer --> Browser: Error message
    Browser --> User: Show password error
end

User -> Browser: Send message to admin
Browser -> WebServer: POST api_messages.php (message)
WebServer -> Database: INSERT INTO messages
Database --> WebServer: Message stored
WebServer --> AdminPanel: Notify admin
AdminPanel --> Database: SELECT messages
Database --> AdminPanel: New messages list
AdminPanel --> AdminPanel: Display conversation
AdminPanel -> Browser: POST reply message
Browser -> WebServer: Receive notification
Browser --> User: Show admin reply

@enduml
"""

diagrams = {
    "01_UseCase_Diagram": use_case_diagram,
    "02_Activity_Diagram": activity_diagram,
    "03_Class_Diagram": class_diagram,
    "04_Sequence_Diagram": sequence_diagram
}

print("🎨 Generating UML Diagrams for Kacchi-Ganj...\n")
print("=" * 60)

for name, content in diagrams.items():
    uml_file = output_dir / f"{name}.puml"
    png_file = output_dir / f"{name}.png"
    
    # Write UML source file
    with open(uml_file, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"\n✓ Created PlantUML source: {name}.puml")
    
    # Try different methods to generate PNG
    generated = False
    
    # Method 1: Try local PlantUML JAR
    if generate_with_plantuml_jar(content, png_file):
        print(f"  📊 Generated PNG using local PlantUML JAR: {name}.png")
        generated = True
    
    # Method 2: Use Kroki.io online service (alternative to plantuml.com)
    if not generated:
        try:
            import requests
            import json
            
            # Encode the diagram
            encoded = plantuml_encode(content)
            
            # Try Kroki.io service
            url = f"https://kroki.io/plantuml/png/{encoded}"
            response = requests.get(url, timeout=10)
            
            if response.status_code == 200:
                with open(png_file, 'wb') as f:
                    f.write(response.content)
                print(f"  📊 Generated PNG using Kroki.io service: {name}.png")
                generated = True
            else:
                print(f"  ⚠️  Kroki.io service returned status {response.status_code}")
        except ImportError:
            print(f"  ℹ️  requests library not installed")
        except Exception as e:
            print(f"  ⚠️  Online service error: {str(e)[:50]}")
    
    if not generated:
        print(f"  💡 To generate PNG: Install PlantUML and run:")
        print(f"     plantuml {uml_file.name}")

print("\n" + "=" * 60)
print(f"\n✅ PlantUML source files created in: {output_dir}")
print(f"   📄 Files: 01_UseCase, 02_Activity, 03_Class, 04_Sequence")
print(f"\n📌 OPTIONS TO GENERATE PNG FILES:")
print(f"\n   1️⃣  Install PlantUML locally (Recommended):")
print(f"       - Download: https://plantuml.com/download")
print(f"       - Run: plantuml {output_dir}\\*.puml")
print(f"\n   2️⃣  Use online PlantUML editor:")
print(f"       - Visit: https://www.plantuml.com/plantuml/uml/")
print(f"       - Copy .puml file content and paste")
print(f"\n   3️⃣  Use VS Code extension:")
print(f"       - Install 'PlantUML' extension")
print(f"       - Right-click .puml file → Export")
print(f"\n" + "=" * 60)
