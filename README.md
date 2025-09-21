# StockAI  

**StockAI** is a smart stock management system designed for restaurants. It helps track ingredient usage, manage inventory levels, and automate the process of purchasing supplies, ensuring smooth restaurant operations.  

## 📌 Project Description  

In restaurants, every dish consumes a specific amount of ingredients (e.g., 50g of meat, 50g of pasta). StockAI starts with an initial stock audit stored in the database and then automatically deducts ingredients based on dish preparation.  

By doing so, it provides accurate insights into:  
- What stock is being consumed.  
- When to reorder supplies.  
- Which dishes can no longer be served due to low stock.  

## 🛠️ Problem It Addresses  

Restaurants often struggle with:  
- Manual stock tracking, which is prone to errors.  
- Running out of essential ingredients during service.  
- Inefficient ordering processes with suppliers.  
- Time-consuming invoice/receipt management.  

## 💡 Proposed Solution  

StockAI simplifies and automates inventory management by:  
- **Stock Tracking:** Automatically deducts ingredient quantities whenever a dish is prepared.  
- **Automated Reordering:** Uses a cron job to analyze stock and trigger orders via API, email, or by generating a PDF shopping list if suppliers are unavailable.  
- **AI-powered Receipt Uploads:** Upload a receipt, and StockAI uses AI to extract the purchased items and update the stock database.  
- **User Interface Tabs:**  
  - **Orders Tab:** Place and track orders.  
  - **Receipts Tab:** Upload receipts to update stock.  
  - **Stock Tab:** View available stock in real-time.  
  - **Invoices Tab:** Access previously uploaded invoices.  
  - **Messages Tab:** View all communication sent to suppliers, including automated reorders and confirmations.  

## 🚀 Features  

- Smart stock deduction per dish.  
- Automated supplier ordering (API, email, or shopping list).  
- AI-powered receipt recognition.  
- Easy-to-use web interface with multiple tabs.  
- Full visibility of past invoices and receipts.  
- Centralized log of all supplier messages.  
