# User Story: Loyalty System Database Design



1. Customers Table:

   - id
   - name
   - contact_details
   - membership_status


2. Rewards Table:

   - id
   - name
   - description
   - point_cost

3. Customer Rewards Table:

   - id 
   - customer_id
   - reward_id
   - redemption

3. Transactions Table:

   - id 
   - customer_id
   - transaction_date (timestamp)
   - purchase_amount

3. Loyalty Table:

   - id 
   - customer_id
   - transaction_id 
   - points
   
4. Privileges Table:
   - id
   - privilege

5. Tiered Table:

   - id 
   - name
   - reward_id 

6. Tiered Privileges Table:
    - tier_id
    - privilege_id

7. Memberships Table:
   - id
   - customer_id
   - tier_id
   - start_date
   - end_date
   
