## Loyalty System

### Customer Management

###### we can separate the contact details into a separate table and link it to the customer table using a foreign key. This will allow us to store multiple contact details for each customer.

1. Customer Table
    - id : int (primary key)
    - name : varchar (255)
    - contact_details : json (object)
        - phone
        - email
        - address
        - city
        - state
        - zip
        - country
    - membership status : varchar (255)
        - active
        - inactive
        - suspended
        - banned
    - points_earned : int (default 0)


### Rewards Program

1. Rewards_Catalog
    - id : int (primary key)
    - name : varchar (255)
    - description : text 
    - point cost : int 

2. Customer_Rewards
   - id : int (primary key)
   - customer_id : int (foreign key to customer table)
   - reward_id : int (foreign key to rewards_catalog table)
   - earned_date : datetime && null
   - redeemed_date : datetime && null


### Transaction Tracking

1. Transactions Table
    - id : int (primary key)
    - customer_id : int (foreign key to customer table)
    - purchase_amount : int
    - points_earned : int
    - Created_at : datetime
    - Updated_at : datetime


### Tiered Membership Levels

1. Privileges Table
    - id : int (primary key)
    - name : varchar (255)
      - free shipping
      - free returns
      - free gift
    - description : text && null

2. Tiered Table
    - id : int (primary key)
    - name : varchar (255)
      - silver
      - gold
      - platinum
    - description : text && null

3. Tiered_privileges Table
    - tier_id : int (foreign key to tiered table)
    - privilege_id : int (foreign key to privileges table)

4. Customer_Membership
    - id : int (primary key)
    - customer_id : int (foreign key to customer table)
    - tier_id : int (foreign key to tiered table)
    - start_date : datetime
    - end_date : datetime && null


#### Relationships

1. Customer Table
    - has many transactions
    - has many customer_rewards
    - has many customer_membership

2. Rewards_Catalog
    - has many customer_rewards

3. Customer_Rewards
    - belongs to customer
    - belongs to rewards_catalog

4. Transactions Table
    - belongs to customer

5. Privileges Table
    - has many tiered 

6. Tiered Table
    - has many privileges
    - has many customer_membership

7. Customer_Membership
    - belongs to customer
    - belongs to tiered



