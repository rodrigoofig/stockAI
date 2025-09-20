from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from PIL import Image
import io
import uvicorn
import os
from datetime import datetime
from textract_image_analyzer import TextractAnalyzer
import requests

app = FastAPI(
    title="Image Reader API",
    description="API for reading uploaded images (preparing for OCR)",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=False,
    allow_methods=["*"],
    allow_headers=["*"],
)


# Get current file path information using only os
CURRENT_FILE_PATH = os.path.abspath(__file__)
CURRENT_DIR = os.path.dirname(CURRENT_FILE_PATH)

UPLOAD_DIR = f"{CURRENT_DIR}/uploads"
# Create uploads directory if it doesn't exist (inside receipt folder)
os.makedirs(UPLOAD_DIR, exist_ok=True)


API_BASE_URL = "http://stockai-database/api"
STOCK_SERVICE_URL = API_BASE_URL + "/stocks"


@app.get("/")
async def root():
    return {
        "message": "Image Reader API is running - ready to read images for text extraction"
    }


@app.post("/read-image/")
async def read_image(file: UploadFile = File(...)):
    """
    Upload, read, and store an image file locally for text extraction.

    Args:
        file: The image file to upload

    Returns:
        JSON response with image information and local file path
    """
    # Validate file type - accept common image formats
    allowed_types = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/gif",
        "image/bmp",
        "image/webp",
    ]
    if not file.content_type or file.content_type not in allowed_types:
        raise HTTPException(
            status_code=400,
            detail=f"File must be an image. Supported formats: {', '.join(allowed_types)}",
        )

    try:
        # Read the uploaded file
        contents = await file.read()

        # Open image with PIL to validate and get info
        image = Image.open(io.BytesIO(contents))

        # Generate unique filename with timestamp and preserve extension
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        unique_filename = f"{timestamp}_{file.filename or 'upload'}"

        print(UPLOAD_DIR)
        print(unique_filename)

        # Save file locally
        file_path = os.path.join(UPLOAD_DIR, unique_filename)
        with open(file_path, "wb") as f:
            f.write(contents)

        print("File uploaded and saved locally.")

        # Analyze the image using Textract
        analyzer = TextractAnalyzer()
        response = analyzer.analyze_document(file_path)
        results = analyzer.format_results(response, include_raw=True)
        print(results)
        parsed_res = analyzer.parse_receipt(results)

        await update_stock(parsed_res)

        # Get basic image information
        image_info = {
            "filename": file.filename,
            "saved_as": unique_filename,
            "content_type": file.content_type,
            "size_bytes": len(contents),
            "image_format": image.format,
            "image_mode": image.mode,
            "dimensions": {"width": image.width, "height": image.height},
        }

        # File storage information
        file_storage = {
            "local_path": file_path,
            "absolute_path": os.path.abspath(file_path),
            "file_exists": os.path.exists(file_path),
            "file_size": os.path.getsize(file_path),
            "ready_for_ocr": True,
        }

        print(f"Image saved to {file_path}")

        return JSONResponse(
            content={
                "success": True,
                "message": "Image uploaded, read, and saved locally - ready for text extraction",
                "image_info": image_info,
                "file_storage": file_storage,
            }
        )

    except Exception as e:
        raise HTTPException(
            status_code=500, detail=f"Error reading/saving image: {str(e)}"
        )


async def update_stock(products_restocked):
    """
    Syncs purchased products with the stock system.

    - If a product exists in stock, updates its quantity via PUT.
    - If a product does not exist, creates a new stock entry via POST.
    """

    print("UPDATING STOCK WITH:")
    print(products_restocked)

    current_stocks = get_stocks()
    print("CURRENT STOCKS:")
    print(current_stocks)

    for product in products_restocked:
        product_name = product["name"]
        quantity_restocked = product["quantity"]

        # Try to find product in CURRENT_STOCK
        stock_item = next(
            (
                item
                for item in current_stocks
                if product_name.lower() in item["name"].lower()
            ),
            None,
        )

        print("STOCK ITEM:")
        print(stock_item)

        if stock_item:
            # ✅ Product exists: update its quantity (decrement by quantity bought)
            print("✅ Product found in stock, updating quantity.")

            stock_quantity = stock_item["quantity"]

            if stock_item["unit"] in ["ml", "grams"]:
                updated_quantity = stock_quantity + quantity_restocked * 1000
            else:
                updated_quantity = stock_quantity + quantity_restocked

            if updated_quantity < 0:
                updated_quantity = 0  # avoid negative stock

            stock_item["quantity"] = updated_quantity  # update local copy

            # Send PUT request
            response = requests.put(
                f"{STOCK_SERVICE_URL}/{stock_item['id']}", json=stock_item
            )

            print(
                f"PUT {product_name} => {response.status_code} (new quantity: {updated_quantity})"
            )

        else:
            # ❌ Product does not exist: create a new entry with default data
            print("❌ Product not found in stock, creating new entry.")
            # TODO - implement creating new stock items

            # new_stock_item = {
            #     "name": product_name,
            #     "quantity": quantity_restocked,
            #     "unit": "units",  # default assumption, adjust if needed
            #     "supplier_id": os.getenv("DEFAULT_SUPPLIER_ID"),  # default supplier
            #     "product_id": "",  # could be linked later
            # }

            # print(new_stock_item)

            # # Send POST request (in an array, as specified)
            # response = requests.post(STOCK_SERVICE_URL, json=new_stock_item)

            # print(
            #     f"POST {product_name} => {response.status_code} (created with quantity: {quantity_restocked})"
            # )


def get_stocks():
    """
    Makes a request to the stock service to get the stocks
    """
    try:
        response = requests.get(STOCK_SERVICE_URL)
        response.raise_for_status()
        return response.json()

    except requests.RequestException as e:
        print(f"Error fetching stocks: {e}")
        return {"error": str(e)}


if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
