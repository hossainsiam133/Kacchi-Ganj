#!/usr/bin/env python3
"""
Generate UML Diagrams for Kacchi-Ganj Food Ordering System
Creates: Use Case, Activity, Class, and Sequence Diagrams
"""

import subprocess
import os
from pathlib import Path

# Define the output directory
output_dir = Path(__file__).parent / "UML_Diagrams"
output_dir.mkdir(exist_ok=True)

# ============================================================================
# 1. USE CASE DIAGRAM
# ============================================================================
use_case_diagram = """@startuml UseCase
!theme plain
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

actor User
actor Admin

User --> (Register)
User --> (Login)
User --> (Browse Menu)
User --> (Add to Cart)
User --> (View Cart)
User --> (Update Cart Items)
User --> (Checkout)
User --> (Place Order)
User --> (View Order Status)
User --> (Send Message to Admin)
User --> (Receive Messages)

Admin --> (Login)
Admin --> (Add Product)
Admin --> (Edit Product)
Admin --> (Delete Product)
Admin --> (View Orders)
Admin --> (Manage Users)
Admin --> (Receive Messages from Users)
Admin --> (Send Messages to Users)
Admin --> (View Dashboard)

usecase UC1 as "Authentication System"
usecase UC2 as "Product Management"
usecase UC3 as "Order Management"
usecase UC4 as "Messaging System"

(Register) ..> UC1 : uses
(Login) ..> UC1 : uses
(Browse Menu) ..> UC2 : uses
(Add Product) ..> UC2 : uses
(Edit Product) ..> UC2 : uses
(Delete Product) ..> UC2 : uses
(Place Order) ..> UC3 : uses
(View Order Status) ..> UC3 : uses
(View Orders) ..> UC3 : uses
(Send Message to Admin) ..> UC4 : uses
(Receive Messages from Users) ..> UC4 : uses

@enduml
"""

# ============================================================================
# 2. ACTIVITY DIAGRAM - Order Placement Flow
# ============================================================================
activity_diagram = """@startuml Activity
!theme plain
skinparam backgroundColor #FEFEFE
skinparam activity {
    backgroundColor #D4AF37
    borderColor #8B6914
    fontColor #fff
}

start
:User Logs In;
:Browse Menu;
:View Product Details;
:Add Product to Cart;
:Review Cart Items;
if (Cart Empty?) then (Yes)
    :Display Empty Message;
    :Prompt to Add Items;
    backward :Browse Menu;
else (No)
    :Proceed to Checkout;
    :Enter Delivery Address;
    :Select Payment Method;
    :Verify Password;
    if (Password Valid?) then (Yes)
        :Calculate Total (with shipping);
        :Place Order;
        :Generate Receipt;
        :Send Confirmation Message;
        :Clear Cart;
        :Display Order Success;
    else (No)
        :Show Error Message;
        :Prompt Retry;
        backward :Verify Password;
    endif
endif
stop

@enduml
"""

# ============================================================================
# 3. CLASS DIAGRAM
# ============================================================================
class_diagram = """@startuml Class
!theme plain
skinparam backgroundColor #FEFEFE
skinparam class {
    backgroundColor #D4AF37
    borderColor #8B6914
    fontColor #000
}

class User {
    - id: int
    - name: string
    - email: string
    - password: string
    - user_type: enum(user, admin)
    - created_at: datetime
    --
    + register()
    + login()
    + updateProfile()
    + sendMessage()
    + receiveMessage()
}

class Product {
    - id: int
    - name: string
    - price: decimal
    - image: string
    - product_details: text
    - admin_id: int
    --
    + getDetails()
    + updatePrice()
    + deleteProduct()
}

class Cart {
    - id: int
    - user_id: int
    - pid: int
    - name: string
    - price: decimal
    - quantity: int
    - image: string
    - created_at: datetime
    --
    + addItem()
    + removeItem()
    + updateQuantity()
    + calculateTotal()
    + clearCart()
}

class Order {
    - id: int
    - user_id: int
    - name: string
    - email: string
    - phone: string
    - address: string
    - payment_method: string
    - total_price: decimal
    - product_list: text
    - placed_on: date
    - status: enum(pending, confirmed, delivered)
    --
    + placeOrder()
    + updateStatus()
    + generateReceipt()
}

class Message {
    - id: int
    - sender_id: int
    - receiver_id: int
    - message: text
    - timestamp: datetime
    - is_read: boolean
    --
    + sendMessage()
    + markAsRead()
    + getConversation()
    + deleteMessage()
}

class Admin {
    - id: int (inherited from User)
    - admin_level: int
    --
    + addProduct()
    + editProduct()
    + deleteProduct()
    + viewOrders()
    + manageUsers()
    + respondToMessage()
    + viewDashboard()
}

User "1" --> "*" Cart : has
User "1" --> "*" Order : places
User "1" --> "*" Message : sends
Product "1" --> "*" Cart : added_to
Product "1" <-- "*" Order : contains
Admin --|> User : extends
Message "*" --> "1" User : from_user
Message "*" --> "1" User : to_admin

@enduml
"""

# ============================================================================
# 4. SEQUENCE DIAGRAM - User Order & Message Flow
# ============================================================================
sequence_diagram = """@startuml Sequence
!theme plain
skinparam backgroundColor #FEFEFE
skinparam sequenceActor backgroundColor #FFE6CC
skinparam sequenceParticipant backgroundColor #D4AF37
skinparam sequenceBorder backgroundColor #8B6914

actor User
participant "Web Browser"
participant "menu.php"
participant "cart.php"
participant "checkout.php"
database "Database"
participant "Admin Panel"

User -> "Web Browser": Login
"Web Browser" -> "menu.php": Load Menu
"menu.php" -> "Database": SELECT products
"Database" --> "menu.php": Product List
"menu.php" --> "Web Browser": Display Products

User -> "Web Browser": Add to Cart
"Web Browser" -> "cart.php": POST add_to_cart
"cart.php" -> "Database": INSERT INTO cart
"Database" --> "cart.php": Success
"cart.php" --> "Web Browser": Item Added

User -> "Web Browser": View Cart
"Web Browser" -> "cart.php": GET cart page
"cart.php" -> "Database": SELECT * FROM cart
"Database" --> "cart.php": Cart Items
"cart.php" --> "Web Browser": Display Cart

User -> "Web Browser": Update Quantity
"Web Browser" -> "cart.php": POST update_qty
"cart.php" -> "Database": UPDATE cart SET quantity
"Database" --> "cart.php": Updated
"cart.php" --> "Web Browser": Refresh Cart

User -> "Web Browser": Proceed to Checkout
"Web Browser" -> "checkout.php": POST order_data
"checkout.php" -> "Database": SELECT * FROM users
"Database" --> "checkout.php": User Data
"checkout.php" -> "Database": INSERT INTO order
"Database" --> "checkout.php": Order Placed
"checkout.php" -> "Database": DELETE FROM cart
"Database" --> "checkout.php": Cart Cleared
"checkout.php" -> "Web Browser": Order Confirmation

"Web Browser" -> "Admin Panel": Order Notification
"Admin Panel" -> "Database": SELECT * FROM order
"Database" --> "Admin Panel": New Order
"Admin Panel" --> User: Order Status Update

@enduml
"""

diagrams = {
    "UseCase_Diagram": use_case_diagram,
    "Activity_Diagram": activity_diagram,
    "Class_Diagram": class_diagram,
    "Sequence_Diagram": sequence_diagram
}

# Generate PNG files using PlantUML
print("🎨 Generating UML Diagrams...\n")

for name, content in diagrams.items():
    uml_file = output_dir / f"{name}.puml"
    png_file = output_dir / f"{name}.png"
    
    # Write UML source file
    with open(uml_file, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✓ Created {uml_file.name}")
    
    # Generate PNG using PlantUML (online service via Docker or local jar)
    # Try using online service first
    try:
        # Method 1: Using plantuml.com online service
        import urllib.request
        import urllib.parse
        
        # Encode the diagram
        encoded = urllib.parse.quote(content.encode('utf-8'))
        url = f"http://www.plantuml.com/plantuml/png/{encoded}"
        
        # Download the image
        urllib.request.urlretrieve(url, png_file)
        print(f"  📊 Generated PNG: {png_file.name}\n")
        
    except Exception as e:
        print(f"  ⚠️  Online service failed: {e}")
        print(f"  💡 Alternative: Install PlantUML locally and run:")
        print(f"     plantuml {uml_file}\n")

print(f"\n✅ UML source files saved in: {output_dir}")
print(f"   Diagrams: UseCase, Activity, Class, Sequence")
print(f"\n📝 To generate PNG files, install PlantUML and run:")
print(f"   cd {output_dir}")
print(f"   plantuml *.puml")
