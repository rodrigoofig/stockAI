from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.responses import JSONResponse
from PIL import Image
import io
import uvicorn
import os
from datetime import datetime

app = FastAPI(title="Image Reader API", description="API for reading uploaded images (preparing for OCR)")

# Create uploads directory if it doesn't exist (inside receipt folder)
UPLOAD_DIR = "stockAI-backend/receipt/uploads"
os.makedirs(UPLOAD_DIR, exist_ok=True)

@app.get("/")
async def root():
    return {"message": "Image Reader API is running - ready to read images for text extraction"}

@app.post("/read-image/")
async def read_image(file: UploadFile = File(...)):
    """
    Upload, read, and store an image file locally for text extraction.
    
    Args:
        file: The image file to upload
        
    Returns:
        JSON response with image information and local file path
    """
    # Validate file type
    if not file.content_type or not file.content_type.startswith('image/'):
        raise HTTPException(status_code=400, detail="File must be an image")
    
    try:
        # Read the uploaded file
        contents = await file.read()
        
        # Open image with PIL to validate and get info
        image = Image.open(io.BytesIO(contents))
        
        # Generate unique filename with timestamp
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        unique_filename = f"{timestamp}_{file.filename or 'upload'}"
        
        # Save file locally
        file_path = os.path.join(UPLOAD_DIR, unique_filename)
        with open(file_path, "wb") as f:
            f.write(contents)
        
        # Get basic image information
        image_info = {
            "filename": file.filename,
            "saved_as": unique_filename,
            "content_type": file.content_type,
            "size_bytes": len(contents),
            "image_format": image.format,
            "image_mode": image.mode,
            "dimensions": {
                "width": image.width,
                "height": image.height
            }
        }
        
        # File storage information
        file_storage = {
            "local_path": file_path,
            "absolute_path": os.path.abspath(file_path),
            "file_exists": os.path.exists(file_path),
            "file_size": os.path.getsize(file_path),
            "ready_for_ocr": True
        }

        print(f"Image saved to {file_path}")
        
        return JSONResponse(content={
            "success": True,
            "message": "Image uploaded, read, and saved locally - ready for text extraction",
            "image_info": image_info,
            "file_storage": file_storage
        })
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error reading/saving image: {str(e)}")

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
