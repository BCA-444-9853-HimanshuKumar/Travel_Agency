# DATA FLOW DIAGRAM (DFD) PROMPTS

## DFD CREATION PROMPTS FOR TRAVEL AGENCY MANAGEMENT SYSTEM

### 1. CONTEXT LEVEL DFD (LEVEL 0)
```
Create a Context Level DFD for a Travel Agency Management System with the following entities:

External Entities:
- Customer
- Travel Agency Staff
- Payment Gateway
- Email Service
- SMS Service

Central Process: Travel Agency Management System

Data Flows:
- Customer provides registration details
- Customer makes booking request
- Customer submits payment
- Customer receives confirmation
- Staff manages packages
- Staff processes bookings
- Payment gateway processes transactions
- Email service sends notifications
- SMS service sends OTP codes

Requirements:
- Use standard DFD notation (Gane & Sarson)
- Label all data flows clearly
- Show single process bubble with system name
- Connect all external entities to the central process
- Use proper arrow directions for data flow
```

### 2. LEVEL 1 DFD - USER MANAGEMENT MODULE
```
Create a Level 1 DFD for User Management Module with the following processes:

Processes:
1. User Registration
2. Login Authentication
3. OTP Verification
4. Profile Management

Data Stores:
- Users Database
- OTP Database
- Session Database

External Entities:
- Customer
- Email Service
- SMS Service

Data Flows:
- Registration details → User Registration
- Login credentials → Login Authentication
- OTP code → OTP Verification
- Profile updates → Profile Management
- User data ← Users Database
- OTP data ← OTP Database
- Session data ← Session Database
- Verification emails → Email Service
- SMS messages → SMS Service

Requirements:
- Show all processes with numbers (P1, P2, P3, P4)
- Connect processes to data stores with double lines
- Use proper DFD symbols for processes, data stores, external entities
- Label all data flows with descriptive names
```

### 3. LEVEL 1 DFD - BOOKING SYSTEM MODULE
```
Create a Level 1 DFD for Booking System Module with the following components:

Processes:
1. Package Search
2. Booking Creation
3. Payment Processing
4. Booking Confirmation

Data Stores:
- Packages Database
- Bookings Database
- Payments Database
- Inventory Database

External Entities:
- Customer
- Payment Gateway
- Email Service

Data Flows:
- Search criteria → Package Search
- Package details ← Package Search
- Booking request → Booking Creation
- Payment details → Payment Processing
- Transaction data ← Payment Gateway
- Booking confirmation ← Booking Confirmation
- Email notifications → Email Service

Requirements:
- Show process sequence and dependencies
- Include error handling flows
- Show data transformation between processes
- Use proper DFD notation throughout
```

### 4. LEVEL 2 DFD - AUTHENTICATION PROCESS
```
Create a Level 2 DFD for Authentication Process showing detailed flow:

Sub-processes:
1. Validate Credentials
2. Generate OTP
3. Send OTP
4. Verify OTP
5. Create Session

Data Stores:
- Users Database
- OTP Database
- Session Database
- Login Attempts Database

Data Flows:
- Username/Password → Validate Credentials
- User validation result ← Users Database
- Phone number → Generate OTP
- OTP code ← Generate OTP
- OTP data → OTP Database
- SMS request → Send OTP
- OTP verification request → Verify OTP
- OTP validation result ← OTP Database
- Session creation request → Create Session
- Session data ← Session Database
- Login attempt record → Login Attempts Database

Requirements:
- Show decision points (diamond shapes)
- Include error flows and retry mechanisms
- Show timeout handling for OTP
- Include security validation steps
```

### 5. LEVEL 1 DFD - AI CHATBOT MODULE
```
Create a Level 1 DFD for AI Chatbot Module:

Processes:
1. Message Processing
2. Pattern Matching
3. Response Generation
4. Learning Update

Data Stores:
- Conversation History
- Pattern Database
- Response Templates
- Learning Data

External Entities:
- Customer
- Admin Dashboard

Data Flows:
- User messages → Message Processing
- Processed message → Pattern Matching
- Matched patterns ← Pattern Database
- Response request → Response Generation
- Response templates ← Response Templates
- Chat responses ← Response Generation
- Learning data → Learning Update
- Analytics data ← Learning Update
- Admin reports → Admin Dashboard

Requirements:
- Show AI/ML processing flow
- Include data learning loops
- Show real-time processing
- Include analytics and reporting
```

### 6. LEVEL 1 DFD - ADMIN DASHBOARD MODULE
```
Create a Level 1 DFD for Admin Dashboard Module:

Processes:
1. User Management
2. Package Management
3. Booking Management
4. Report Generation
5. Analytics Processing

Data Stores:
- Users Database
- Packages Database
- Bookings Database
- Payments Database
- Reports Database

External Entities:
- Admin User
- Email Service

Data Flows:
- Admin commands → User Management
- User data updates ← Users Database
- Package updates → Package Management
- Package data ← Packages Database
- Booking requests → Booking Management
- Booking data ← Bookings Database
- Report requests → Report Generation
- Analytics requests → Analytics Processing
- Email notifications → Email Service

Requirements:
- Show administrative workflows
- Include data validation processes
- Show report generation flow
- Include security access controls
```

### 7. INTEGRATED SYSTEM DFD
```
Create an Integrated System DFD showing all modules working together:

Main Processes:
- User Management System
- Booking System
- Payment System
- AI Chatbot System
- Admin Dashboard
- Notification System

Shared Data Stores:
- Central User Database
- Transaction Database
- System Logs
- Configuration Database

Inter-module Data Flows:
- User authentication data between modules
- Booking status updates across systems
- Payment confirmations to all relevant modules
- AI chatbot integration with booking system
- Admin dashboard data aggregation

Requirements:
- Show system integration points
- Include data synchronization flows
- Show real-time data sharing
- Include backup and recovery flows
```

## DFD CREATION GUIDELINES

### SYMBOLS AND NOTATION:
- **Process**: Circle or rounded rectangle with process name and number
- **Data Store**: Two parallel lines with data store name
- **External Entity**: Rectangle with entity name
- **Data Flow**: Arrow with data flow label
- **Decision Point**: Diamond shape (for Level 2+ DFDs)

### BEST PRACTICES:
1. **Naming Conventions**: Use verb-noun combinations for processes
2. **Data Flow Labels**: Be specific about what data is flowing
3. **Leveling**: Maintain consistency between DFD levels
4. **Balancing**: Ensure inputs and outputs balance at each level
5. **Clarity**: Avoid crossing data flows where possible

### VALIDATION CHECKLIST:
- [ ] All external entities are identified
- [ ] All processes are numbered and named
- [ ] Data stores are properly labeled
- [ ] All data flows are labeled and directed
- [ ] No data flows between external entities
- [ ] All inputs have corresponding outputs
- [ ] Consistent notation throughout
- [ ] Proper leveling hierarchy maintained

### TOOLS FOR CREATING DFDS:
- **Microsoft Visio**: Professional DFD creation
- **Lucidchart**: Online collaborative diagramming
- **Draw.io**: Free online diagram tool
- **SmartDraw**: Automated DFD generation
- **Edraw Max**: Comprehensive diagramming software

### EXAMPLE DFD STRUCTURE:
```
[External Entity] → (Data Flow) → [Process] → (Data Flow) → [Data Store]
                                    ↓
                              (Data Flow) → [External Entity]
```

These prompts provide comprehensive guidance for creating detailed Data Flow Diagrams for your Travel Agency Management System at all levels of abstraction.
