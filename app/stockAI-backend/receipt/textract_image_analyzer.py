#!/usr/bin/env python3
"""
AWS Textract Image Analyzer

This script analyzes images using AWS Textract to extract text, tables, and forms.
It supports both local image files and images from URLs.
Automatically loads AWS credentials from .env file if present.

Requirements:
- boto3
- Pillow (PIL)
- requests (for URL images)
- python-dotenv (for .env file support)

Usage:
    python textract_image_analyzer.py <image_path_or_url>
    python textract_image_analyzer.py --help
"""

import argparse
import json
import os
import sys
from pathlib import Path
from typing import Dict, List, Optional, Union

import boto3
from botocore.exceptions import BotoCoreError, ClientError, NoCredentialsError
from PIL import Image
import requests
from io import BytesIO

# Load environment variables from .env file
try:
    from dotenv import load_dotenv
    load_dotenv()
    print("✓ Loaded environment variables from .env file")
except ImportError:
    print("⚠️  python-dotenv not installed. Install with: pip install python-dotenv")
    print("   Continuing without .env file support...")


class TextractAnalyzer:
    """AWS Textract image analyzer class."""
    
    def __init__(self, region_name: str = None, profile_name: Optional[str] = None):
        """
        Initialize the Textract analyzer.
        
        Args:
            region_name: AWS region name (defaults to AWS_DEFAULT_REGION from .env or 'us-east-1')
            profile_name: AWS profile name (optional)
        """
        # Use region from .env file if not specified
        if region_name is None:
            region_name = os.getenv('AWS_DEFAULT_REGION', 'us-east-1')
        
        self.region_name = region_name
        self.profile_name = profile_name
        self.textract_client = None
        self._initialize_client()
    
    def _initialize_client(self):
        """Initialize the AWS Textract client."""
        try:
            # Check if credentials are available
            access_key = os.getenv('AWS_ACCESS_KEY_ID')
            secret_key = os.getenv('AWS_SECRET_ACCESS_KEY')
            session_token = os.getenv('AWS_SESSION_TOKEN')
            
            if access_key and secret_key:
                print(f"✓ Using AWS credentials from environment variables")
                # Create session with explicit credentials
                session = boto3.Session(
                    aws_access_key_id=access_key,
                    aws_secret_access_key=secret_key,
                    aws_session_token=session_token,
                    region_name=self.region_name
                )
            else:
                print(f"✓ Using AWS credentials from default configuration")
                # Use default credential chain (AWS CLI, IAM roles, etc.)
                session = boto3.Session(profile_name=self.profile_name)
            
            self.textract_client = session.client('textract', region_name=self.region_name)
            print(f"✓ AWS Textract client initialized for region: {self.region_name}")
            
        except NoCredentialsError:
            print("❌ Error: AWS credentials not found.")
            print("   Please ensure your .env file contains:")
            print("   AWS_ACCESS_KEY_ID=your_access_key")
            print("   AWS_SECRET_ACCESS_KEY=your_secret_key")
            print("   AWS_DEFAULT_REGION=us-east-1")
            print("   # Optional: AWS_SESSION_TOKEN=your_session_token")
            print("   Or run 'aws configure' to set up credentials.")
            sys.exit(1)
        except Exception as e:
            print(f"❌ Error initializing AWS client: {e}")
            sys.exit(1)
    
    def _load_image_from_file(self, image_path: str) -> bytes:
        """Load image from local file."""
        try:
            path = Path(image_path)
            if not path.exists():
                raise FileNotFoundError(f"Image file not found: {image_path}")
            
            # Validate it's an image file
            with Image.open(path) as img:
                img.verify()
            
            with open(path, 'rb') as f:
                return f.read()
        except Exception as e:
            raise ValueError(f"Error loading image from file: {e}")
    
    def _load_image_from_url(self, image_url: str) -> bytes:
        """Load image from URL."""
        try:
            response = requests.get(image_url, timeout=30)
            response.raise_for_status()
            
            # Validate it's an image
            with Image.open(BytesIO(response.content)) as img:
                img.verify()
            
            return response.content
        except Exception as e:
            raise ValueError(f"Error loading image from URL: {e}")
    
    def _load_image(self, image_source: str) -> bytes:
        """Load image from file or URL."""
        if image_source.startswith(('http://', 'https://')):
            return self._load_image_from_url(image_source)
        else:
            return self._load_image_from_file(image_source)
    
    def analyze_document(self, image_source: str, feature_types: List[str] = None) -> Dict:
        """
        Analyze document using AWS Textract.
        
        Args:
            image_source: Path to image file or URL
            feature_types: List of feature types to detect (TABLES, FORMS, etc.)
            
        Returns:
            Dictionary containing the analysis results
        """
        if feature_types is None:
            feature_types = ['TABLES', 'FORMS']
        
        try:
            # Load image
            print(f"📁 Loading image: {image_source}")
            image_bytes = self._load_image(image_source)
            
            # Call Textract
            print("🔍 Analyzing document with AWS Textract...")
            response = self.textract_client.analyze_document(
                Document={'Bytes': image_bytes},
                FeatureTypes=feature_types
            )
            
            print("✓ Analysis completed successfully!")
            return response
            
        except ClientError as e:
            error_code = e.response['Error']['Code']
            if error_code == 'InvalidParameterException':
                print(f"❌ Error: Invalid image format or size. {e}")
            elif error_code == 'AccessDeniedException':
                print(f"❌ Error: Access denied. Check your AWS permissions. {e}")
            elif error_code == 'ThrottlingException':
                print(f"❌ Error: Rate limit exceeded. Please try again later. {e}")
            else:
                print(f"❌ AWS Error: {e}")
            sys.exit(1)
        except Exception as e:
            print(f"❌ Error analyzing document: {e}")
            sys.exit(1)
    
    def extract_text(self, blocks: List[Dict]) -> str:
        """Extract plain text from Textract blocks."""
        text_lines = []
        for block in blocks:
            if block['BlockType'] == 'LINE':
                text_lines.append(block['Text'])
        return '\n'.join(text_lines)
    
    def extract_tables(self, blocks: List[Dict]) -> List[List[List[str]]]:
        """Extract tables from Textract blocks."""
        tables = []
        table_blocks = [block for block in blocks if block['BlockType'] == 'TABLE']
        
        for table_block in table_blocks:
            table = []
            # Get all cells in this table
            cells = [block for block in blocks 
                    if block['BlockType'] == 'CELL' and 
                    block.get('Relationships', [{}])[0].get('Ids', [None])[0] == table_block['Id']]
            
            # Group cells by row
            rows = {}
            for cell in cells:
                row_index = cell.get('RowIndex', 0)
                col_index = cell.get('ColumnIndex', 0)
                if row_index not in rows:
                    rows[row_index] = {}
                rows[row_index][col_index] = cell.get('Text', '')
            
            # Convert to list of lists
            for row_index in sorted(rows.keys()):
                row = []
                for col_index in sorted(rows[row_index].keys()):
                    row.append(rows[row_index][col_index])
                table.append(row)
            
            tables.append(table)
        
        return tables
    
    def extract_forms(self, blocks: List[Dict]) -> Dict[str, str]:
        """Extract key-value pairs from forms."""
        forms = {}
        key_blocks = [block for block in blocks if block['BlockType'] == 'KEY_VALUE_SET' and 
                     block.get('EntityTypes', []) == ['KEY']]
        
        for key_block in key_blocks:
            # Find the corresponding value
            value_text = ""
            if 'Relationships' in key_block:
                for relationship in key_block['Relationships']:
                    if relationship['Type'] == 'VALUE':
                        for value_id in relationship['Ids']:
                            value_block = next((b for b in blocks if b['Id'] == value_id), None)
                            if value_block and value_block['BlockType'] == 'KEY_VALUE_SET':
                                # Get text from child blocks
                                for child_id in value_block.get('Relationships', [{}])[0].get('Ids', []):
                                    child_block = next((b for b in blocks if b['Id'] == child_id), None)
                                    if child_block and child_block['BlockType'] == 'WORD':
                                        value_text += child_block['Text'] + " "
            
            key_text = key_block.get('Text', '').strip()
            if key_text and value_text.strip():
                forms[key_text] = value_text.strip()
        
        return forms
    
    def format_results(self, response: Dict, include_raw: bool = False) -> str:
        """Format the analysis results for display."""
        blocks = response.get('Blocks', [])
        
        output = []
        output.append("=" * 60)
        output.append("AWS TEXTRACT ANALYSIS RESULTS")
        output.append("=" * 60)
        
        # Extract text
        text = self.extract_text(blocks)
        if text:
            output.append("\n📝 EXTRACTED TEXT:")
            output.append("-" * 30)
            output.append(text)
        
        # Extract tables
        tables = self.extract_tables(blocks)
        if tables:
            output.append(f"\n📊 EXTRACTED TABLES ({len(tables)} found):")
            output.append("-" * 30)
            for i, table in enumerate(tables, 1):
                output.append(f"\nTable {i}:")
                for row in table:
                    output.append(" | ".join(cell for cell in row))
        
        # Extract forms
        forms = self.extract_forms(blocks)
        if forms:
            output.append(f"\n📋 EXTRACTED FORMS ({len(forms)} key-value pairs):")
            output.append("-" * 30)
            for key, value in forms.items():
                output.append(f"{key}: {value}")
        
        # Raw response (if requested)
        if include_raw:
            output.append(f"\n🔧 RAW RESPONSE:")
            output.append("-" * 30)
            output.append(json.dumps(response, indent=2, default=str))
        
        return "\n".join(output)


def main():
    """Main function to run the script."""
    parser = argparse.ArgumentParser(
        description="Analyze images using AWS Textract (supports .env file)",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
    Examples:
    python textract_image_analyzer.py image.jpg
    python textract_image_analyzer.py https://example.com/image.png
    python textract_image_analyzer.py image.jpg --region us-west-2
    python textract_image_analyzer.py image.jpg --raw --output results.json

    Environment Variables (from .env file):
    AWS_ACCESS_KEY_ID=your_access_key
    AWS_SECRET_ACCESS_KEY=your_secret_key
    AWS_DEFAULT_REGION=us-east-1
    AWS_SESSION_TOKEN=your_session_token (optional)
            """
    )
    
    parser.add_argument('image', help='Path to image file or URL')
    parser.add_argument('--region', help='AWS region (overrides AWS_DEFAULT_REGION from .env)')
    parser.add_argument('--profile', help='AWS profile name (overrides .env credentials)')
    parser.add_argument('--features', nargs='+', choices=['TABLES', 'FORMS'], 
                       default=['TABLES', 'FORMS'], help='Feature types to detect')
    parser.add_argument('--raw', action='store_true', help='Include raw JSON response')
    parser.add_argument('--output', help='Save results to JSON file')
    
    args = parser.parse_args()
    
    # Initialize analyzer
    analyzer = TextractAnalyzer(region_name=args.region, profile_name=args.profile)
    
    # Analyze document
    response = analyzer.analyze_document(args.image, args.features)
    
    # Format and display results
    results = analyzer.format_results(response, include_raw=args.raw)
    print(results)
    
    # Save to file if requested
    if args.output:
        output_data = {
            'image_source': args.image,
            'region': analyzer.region_name,
            'features': args.features,
            'raw_response': response,
            'formatted_results': results
        }
        
        with open(args.output, 'w') as f:
            json.dump(output_data, f, indent=2, default=str)
        print(f"\n💾 Results saved to: {args.output}")


if __name__ == '__main__':
    main()
