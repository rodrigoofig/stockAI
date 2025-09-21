import os
import requests
from dotenv import load_dotenv
from fastapi.responses import JSONResponse

load_dotenv()

AWS_ACCESS_KEY_ID = os.getenv('AWS_ACCESS_KEY_ID')
AWS_SECRET_ACCESS_KEY = os.getenv('AWS_SECRET_ACCESS_KEY')
AWS_SESSION_TOKEN = os.getenv('AWS_SESSION_TOKEN')


API_BASE_URL = "http://localhost:8001/api"
MESSAGE_SERVICE_URL = API_BASE_URL + "/messages"

def get_all_products():
    response = requests.get("http://localhost:8001/api/products")

    return response.json()

def get_all_ingredients():
    response = requests.get("http://localhost:8001/api/ingredients")

    return response.json()

products = get_all_products()
ingredients = get_all_ingredients()

def get_ingredients_per_products(products, ingredients):
    total_list = []

    for product in products:
        product = {
            'id': product['id'],
            'name': product['name'],
            'items': [
                {
                    'id': ingredient['id'],
                    'name': ingredient['name'],
                    'quantity': ingredient['quantity'],
                    'unit': ingredient['unit'],
                    'id': ingredient['id'],

                } 
            for ingredient in ingredients if ingredient['product_id'] == product['id']]
        }

        total_list.append(product)
    return total_list

def get_lowest_quantity_per_ingredient(total_data, ingredients):
    total_list = []

    for ingredient in ingredients:
        lowest_quantity = 99999
        unit = None

        for product in total_data:
            if ingredient['id'] in [ing['id'] for ing in product['items']]:
                ing = [ing for ing in  product['items'] if ing['id'] == ingredient['id']][0]
                if lowest_quantity > ing['quantity']:
                    lowest_quantity = ing['quantity']
                    unit = ing['unit']

        total_list.append({
            'id': ingredient['id'], 
            'name': ingredient['name'], 
            'lowest_quantity': lowest_quantity,
            'minimum_quantity': lowest_quantity * 20,
            'unit': unit
        })

    return total_list


total_list = get_ingredients_per_products(products, ingredients)

lowest_quantity_list = get_lowest_quantity_per_ingredient(total_list, ingredients)


def get_items_needed(products):
    total_data = []
    for product in products:
        for item in product["nearToFinish"]:
            total_data.append(item)
            
    return total_data
        
items_needed = get_items_needed(products)

response = requests.get("http://localhost:8001/api/stocks")

stock = response.json()

import boto3
import json

# Replace with your model ID
MODEL_ID = "anthropic.claude-3-5-sonnet-20240620-v1:0"
REGION = "us-east-1"

# Initialize the Bedrock client
client = boto3.client(
    service_name="bedrock-runtime",
    region_name="us-east-1",
     aws_access_key_id=AWS_ACCESS_KEY_ID,      # optional - set this value if you haven't run `aws configure` 
    aws_secret_access_key=AWS_SECRET_ACCESS_KEY,  # optional - set this value if you haven't run `aws configure`
    aws_session_token=AWS_SESSION_TOKEN,  
)

# Your prompt
prompt = "Explain how neural networks work in simple terms."

# Prepare the request body
body = {
    "anthropic_version": "bedrock-2023-05-31",  # required
    "messages": [
        {
            "role": "assistant",
            "content": [
                {"type": "text", "text": f"""You are a restaurant stock manager. Your job is to check inventory levels of products against minimum required quantities and decide if restocking is needed.

                        Inputs

                        lowest_quantity_list → A dictionary/list with the minimum stock quantity required for each product.

                        items_needed → A list of products that must always be considered essential and stocked.

                        current_stock → A list of products, each with:

                        name

                        current quantity

                        unit type (grams, liters, or units)

                        Rules

                        Compare each product in current_stock to the {lowest_quantity_list}.
                        Have in count this list of predicted items needed {items_needed}.

                        If stock is below the minimum, mark it as needs restock.

                        For restocking:

                        Show the current quantity.

                        Show the adequate quantity to buy (the difference between minimum and current, or more if needed).

                        Output

                        Generate an EMAIL format message that:

                        Has a professional subject line (e.g., “Restock Request - Inventory Shortage”).

                        Contains a table in the email body with columns:

                        Product Name

                        Stock Quantity (with unit)

                        Quantity Needed (to restock to minimum)""" }
            ]
        },
        {
            "role": "user",  # only 'user' or 'assistant'
            "content": [
                {"type": "text", "text": f"Hi stock manager! This is the data stock we got today! {stock} Don't forget to check for all the product the minimum quantity and if the item is not on the stock or is lower than the the minimum_quantity field we need it to be restocked! Please be very serious and generate the email correctly! I just need the email respons with the required products, don't give me product taht already have the minimum quantity required!  Remember if there is more than the minimum please do not consider the products with units required as 0. Thank you!"}
            ]
        }
    ],
    "max_tokens": 1000,
    "temperature": 0.7
}

# Invoke the model
response = client.invoke_model(
    modelId=MODEL_ID,
    body=json.dumps(body),
    contentType="application/json"
)

# Parse and print the output
resp_body = json.loads(response["body"].read())
response = resp_body["content"]


import smtplib

sender = "stockai050@gmail.com"
receiver = "stockai@yopmail.com"
password = "bhay skat wvmc muhx"


with smtplib.SMTP("smtp.gmail.com", 587) as server:
    server.starttls()  # secure connection
    server.login(sender, password)
    server.sendmail(sender, receiver, response[0]["text"])

print("Email sent!")


# POST TO /api/messages
def post_message(msg: str, supplier_name: str, recipient: str):
    "Makes a request to post the message"

#     {
    #     "id": 1,
    #     "title": "Confirmação de Pedido",
    #     "supplierName": "Mercado Central",
    #     "supplierId": 1,
    #     "html": "\u003Ch1\u003EObrigado pela sua compra!\u003C/h1\u003E\u003Cp\u003ESeu pedido foi confirmado.\u003C/p\u003E",
    #     "recipient": "cliente@exemplo.com",
    #     "createdAt": "2025-09-20 22:57:24"
#       }
    payload = {
                "title": "Urgent Restock Request - Critical Inventory Shortage", 
                "supplierName": "Lusiaves",
                "supplierId": 1,
                "html": msg,
                "recipient": recipient
            }
    try:
        response = requests.post(f"{MESSAGE_SERVICE_URL}", json=payload)

        return JSONResponse(
            content={
                "success": True,
                "message": f"An entry was created in Invoices for supplier {supplier_name}",
            }
        )

    except requests.RequestException as e:
        print(f"Error fetching stocks: {e}")
        return {"error": str(e)}


post_message(response[0]["text"], "Lusiaves", receiver)
