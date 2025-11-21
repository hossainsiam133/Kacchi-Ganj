# Kacchi-Ganj Food Ordering System - UML Diagrams

## Overview
This directory contains four comprehensive UML diagrams that document the Kacchi-Ganj food ordering system architecture, workflows, database structure, and interactions.

---

## 📊 Diagrams Generated

### 1. **Use Case Diagram** (`01_UseCase_Diagram.png`)
**Purpose:** Shows all actors (User, Admin) and their interactions with the system.

**Key Use Cases:**
- **User Actions:**
  - Register and Login
  - Browse Menu & View Product Details
  - Add Products to Cart
  - Checkout & Place Orders
  - View Order Status
  - Send/Receive Messages to Admin

- **Admin Actions:**
  - Admin Login
  - Add, Edit, Delete Products
  - View and Manage Orders
  - Manage User Accounts
  - Send/Receive Messages from Users
  - View Dashboard

**Key Relationships:**
- `Browse Menu` includes `View Product Details`
- `Add to Cart` extends `Browse Menu`
- `Place Order` extends `Add to Cart`

---

### 2. **Activity Diagram** (`02_Activity_Diagram.png`)
**Purpose:** Illustrates the complete order placement workflow with decision points.

**Flow Steps:**
1. User Logs In
2. Browse Products
3. Add Items to Cart
4. View Cart Items

**Decision Point 1:** Is cart empty?
   - YES → Show empty message → Go back to browse
   - NO → Continue

5. Proceed to Checkout
6. Enter Delivery Details
7. Select Payment Method
8. Verify Password

**Decision Point 2:** Is password valid?
   - NO → Show error → Retry password entry
   - YES → Continue

9. Place Order
10. Generate Receipt
11. Clear Cart
12. Show Success Message

---

### 3. **Class Diagram** (`03_Class_Diagram.png`)
**Purpose:** Shows the database schema and relationships between entities.

**Classes (Database Tables):**

#### **User**
- Attributes: id, name, email, password, user_type (enum: user/admin)
- Methods: login(), register(), getOrders()

#### **Product**
- Attributes: id, name, price, image, details (text)
- Methods: getDetails(), updatePrice()

#### **Cart**
- Attributes: id, user_id, product_id, quantity, price
- Methods: addItem(), removeItem(), calculateTotal()

#### **Order**
- Attributes: id, user_id, total_price, address, placed_on, status
- Methods: placeOrder(), updateStatus(), generateReceipt()

#### **Message**
- Attributes: id, sender_id, receiver_id, message (text), timestamp, is_read
- Methods: sendMessage(), markAsRead(), getConversation()

**Relationships:**
- User (1) ──→ (*) Cart (has many items)
- User (1) ──→ (*) Order (places many orders)
- User (1) ──→ (*) Message (sends messages)
- Product (1) ──→ (*) Cart (added to many carts)

---

### 4. **Sequence Diagram** (`04_Sequence_Diagram.png`)
**Purpose:** Shows the interaction flow between User, Browser, Server, and Database.

**Actors:**
- User
- Browser (Client-side)
- Server (PHP Backend)
- Database (MySQL)

**Main Sequences:**

**Sequence 1: User Login**
```
User → Browser → Server → Database
(credentials) (authenticate) (SELECT user) (return data)
```

**Sequence 2: Browse Menu**
```
User → Browser → Server → Database → Browser → User
      (getProducts) (SELECT products) (show menu)
```

**Sequence 3: Add to Cart**
```
User → Browser → Server → Database → Browser → User
      (addToCart) (INSERT cart) (confirmation)
```

**Sequence 4: Place Order**
```
User → Browser → Server → Database → Browser → User
      (placeOrder) (INSERT order) (receipt + confirmation)
```

---

## 📁 File Structure

```
UML_Diagrams/
├── 01_UseCase_Diagram.puml          # Source file (PlantUML)
├── 01_UseCase_Diagram.png           # Generated PNG
├── 02_Activity_Diagram.puml         # Source file
├── 02_Activity_Diagram.png          # Generated PNG
├── 03_Class_Diagram.puml            # Source file
├── 03_Class_Diagram.png             # Generated PNG
├── 04_Sequence_Diagram.puml         # Source file
├── 04_Sequence_Diagram.png          # Generated PNG
└── README.md                        # This file
```

---

## 🛠️ Technology Stack Represented

**Frontend:**
- HTML5, CSS3, JavaScript
- Bootstrap Icons, Remixicon
- AJAX for dynamic interactions

**Backend:**
- PHP 7.x+ with MySQLi
- RESTful API endpoints
- Session-based authentication

**Database:**
- MySQL/MariaDB
- Foreign key constraints
- Indexed queries for performance

**Messaging:**
- AJAX polling (3-second intervals)
- Live chat between users and admins
- Message timestamps and read status

---

## 🔑 Key System Features Illustrated

### 1. **Authentication & Authorization**
- User registration and login
- Admin login with separate panel
- Session management

### 2. **Product Management**
- Browse menu with product filtering
- Admin can CRUD products
- Product images and details

### 3. **Shopping Cart**
- Add/remove items
- Update quantities
- Calculate totals with shipping

### 4. **Order Management**
- Order placement with delivery details
- Password verification for security
- Order status tracking
- Receipt generation

### 5. **Messaging System**
- User-to-admin communication
- Live message polling
- Message read/unread status
- Conversation history

---

## 📊 Database Schema Summary

| Table | Primary Key | Key Columns | Purpose |
|-------|-------------|------------|---------|
| `users` | id | name, email, password, user_type | User accounts |
| `products` | id | name, price, image, details | Product catalog |
| `cart` | id | user_id, product_id, quantity, price | Shopping cart items |
| `order` | id | user_id, total_price, address, status | Customer orders |
| `messages` | id | sender_id, receiver_id, message, timestamp | Admin-user chat |

**Foreign Key Constraints:**
```sql
messages.sender_id → users.id
messages.receiver_id → users.id
cart.user_id → users.id
order.user_id → users.id
```

---

## 🔄 Main User Workflows

### **Workflow 1: First-Time User**
```
Register → Login → Browse Menu → Add to Cart → Checkout → Place Order
```

### **Workflow 2: Returning User**
```
Login → Browse Menu → Add to Cart → Checkout → Place Order
```

### **Workflow 3: Customer Support**
```
Login → View Messages → Start New Conversation → Send Message → Receive Reply
```

### **Workflow 4: Admin Management**
```
Admin Login → View Dashboard → Manage Products/Users → View Orders → Reply Messages
```

---

## 💡 Design Highlights

✅ **Modular Architecture** - Separate user and admin interfaces
✅ **Real-time Updates** - AJAX polling for live messaging
✅ **Data Security** - Password verification, foreign key constraints
✅ **User-Friendly Flow** - Simple add-to-cart → checkout workflow
✅ **Admin Control** - Full product and order management
✅ **Error Handling** - Validation at every step
✅ **Responsive Design** - Mobile-friendly interface

---

## 🚀 How to Use These Diagrams

1. **For Development:** Use diagrams to understand system architecture before coding
2. **For Documentation:** Include in project README and technical documentation
3. **For Communication:** Present to stakeholders to explain features
4. **For Testing:** Plan test cases based on workflows shown
5. **For Database:** Reference class diagram when designing queries

---

## 📝 Editing Diagrams

All diagrams are created using **PlantUML**. To modify them:

1. Edit the `.puml` source file in any text editor
2. Visit https://www.plantuml.com/plantuml/uml/
3. Paste the updated code
4. Export as PNG

Or use PlantUML CLI:
```bash
plantuml *.puml
```

---

## 📞 Contact & Support

For questions about the system architecture or diagrams, refer to:
- System Documentation
- Database Schema file
- API Endpoint documentation
- Code comments in respective PHP files

---

**Generated:** November 2025
**System:** Kacchi-Ganj Food Ordering Platform
**Format:** PlantUML (PNG + Source)
