# AWS Textract Image Analyzer

A Python script that uses AWS Textract to extract text, tables, and forms from images. **Now supports loading AWS credentials from a `.env` file!**

## Features

- Extract text from images
- Extract tables with proper formatting
- Extract key-value pairs from forms
- Support for local image files and URLs
- **Automatic .env file support for AWS credentials**
- Configurable AWS region and profile
- JSON output option
- Comprehensive error handling

## Installation

1. Install Python dependencies:

```bash
pip install -r requirements.txt
```

2. Configure AWS credentials using one of these methods:

### Method 1: .env file (Recommended)

Create a `.env` file in the same directory as the script:

```bash
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_SESSION_TOKEN=your_session_token  # Optional, for temporary credentials
```

### Method 2: AWS CLI

```bash
aws configure
```

### Method 3: Environment Variables

```bash
export AWS_ACCESS_KEY_ID=your_access_key
export AWS_SECRET_ACCESS_KEY=your_secret_key
export AWS_DEFAULT_REGION=us-east-1
```

## Usage

### Basic Usage

```bash
python3 textract_image_analyzer.py image.jpg
```

### With URL

```bash
python3 textract_image_analyzer.py https://example.com/image.png
```

### Override .env Region

```bash
python3 textract_image_analyzer.py image.jpg --region us-west-2
```

### Include Raw JSON Response

```bash
python3 textract_image_analyzer.py image.jpg --raw
```

### Save Results to File

```bash
python3 textract_image_analyzer.py image.jpg --output results.json
```

### Specify Feature Types

```bash
python3 textract_image_analyzer.py image.jpg --features TABLES FORMS
```

### Use Specific AWS Profile (overrides .env)

```bash
python3 textract_image_analyzer.py image.jpg --profile my-profile
```

## .env File Support

The script automatically loads AWS credentials from a `.env` file if present. This is the recommended way to manage credentials for development.

### .env File Format

```bash
# Required
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1

# Optional
AWS_SESSION_TOKEN=your_session_token  # For temporary credentials
```

### Priority Order

1. Command line arguments (`--region`, `--profile`)
2. Environment variables
3. .env file
4. AWS CLI configuration
5. IAM roles (if running on EC2)

## Command Line Options

- `image`: Path to image file or URL (required)
- `--region`: AWS region (overrides AWS_DEFAULT_REGION from .env)
- `--profile`: AWS profile name (overrides .env credentials)
- `--features`: Feature types to detect (TABLES, FORMS)
- `--raw`: Include raw JSON response
- `--output`: Save results to JSON file

## Supported Image Formats

- JPEG
- PNG
- PDF (first page only)
- TIFF
- BMP
- GIF

## AWS Permissions Required

The script requires the following AWS permissions:

- `textract:AnalyzeDocument`

## Error Handling

The script includes comprehensive error handling for:

- Invalid image formats
- Missing AWS credentials
- Network connectivity issues
- AWS service errors
- File not found errors
- .env file loading issues

## Example Output

```
✓ Loaded environment variables from .env file
✓ Using AWS credentials from environment variables
✓ AWS Textract client initialized for region: us-east-1
📁 Loading image: sample.jpg
🔍 Analyzing document with AWS Textract...
✓ Analysis completed successfully!

============================================================
AWS TEXTRACT ANALYSIS RESULTS
============================================================

📝 EXTRACTED TEXT:
----------------------------------------------
Sample Document
This is a test document with some text.
It contains multiple lines of content.

📊 EXTRACTED TABLES (1 found):
------------------------------------------

Table 1:
Name | Age | City
John | 25 | New York
Jane | 30 | Los Angeles

📋 EXTRACTED FORMS (2 key-value pairs):
----------------------------------------------
Date: 2024-01-15
Status: Active
```

## Troubleshooting

### .env File Not Loading

- Ensure `python-dotenv` is installed: `pip install python-dotenv`
- Check that `.env` file is in the same directory as the script
- Verify `.env` file format (no spaces around `=`)

### AWS Credentials Issues

- Verify credentials in `.env` file are correct
- Check AWS region is supported by Textract
- Ensure IAM user has `textract:AnalyzeDocument` permission

### Image Loading Issues

- Verify image file exists and is readable
- Check image format is supported
- For URLs, ensure the URL is accessible

## Quick Start (Easy Way)

Use the wrapper script to avoid virtual environment issues:

```bash
# Run the script (automatically activates virtual environment)
./run_textract.sh image.jpg

# Get help
./run_textract.sh --help

# Analyze with options
./run_textract.sh image.jpg --raw --output results.json
```

## Manual Usage (If you prefer)

If you want to run the script manually, always activate the virtual environment first:

```bash
# Activate virtual environment
source venv/bin/activate

# Then run the script
python textract_image_analyzer.py image.jpg

# Deactivate when done
deactivate
```

## Troubleshooting

### "ModuleNotFoundError: No module named 'boto3'"

This error means you're running the script outside the virtual environment.

**Solution:** Always run one of these commands:

```bash
# Option 1: Use the wrapper script (recommended)
./run_textract.sh image.jpg

# Option 2: Manually activate virtual environment
source venv/bin/activate
python textract_image_analyzer.py image.jpg
```

### Virtual Environment Not Working

If you're still having issues, recreate the virtual environment:

```bash
rm -rf venv
./setup.sh
```
