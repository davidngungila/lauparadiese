# PDF Document System

This directory contains reusable PDF templates for the Lau Paradise Adventures system.

## Structure

### Base Layout
- **`layout.blade.php`** - Base PDF layout that can be extended by all PDF documents
  - Professional header with company information
  - Consistent styling and formatting
  - Reusable sections and components
  - Footer with generation timestamp

### Document Templates
- **`admin/quotations/pdf.blade.php`** - Quotation PDF template
- **`invoice-example.blade.php`** - Example invoice template (for reference)

## Usage

### Creating a New PDF Document

1. **Extend the base layout:**
```blade
@extends('pdf.layout')

@section('title', 'Your Document Title')
@section('document-type', 'DOCUMENT TYPE')
@section('document-number', 'DOC-001')
@section('document-date', date('F d, Y'))
@section('document-status', 'status') // Optional
```

2. **Add your content:**
```blade
@section('content')
    <!-- Your content here -->
@endsection
```

3. **Optional footer content:**
```blade
@section('footer-extra')
    Additional footer information
@endsection
```

### Generating PDFs in Controllers

```php
use Barryvdh\DomPDF\Facade\Pdf;

// Download PDF
public function downloadPDF($id)
{
    $data = Model::findOrFail($id);
    $pdf = Pdf::loadView('path.to.pdf.template', compact('data'));
    return $pdf->download('filename.pdf');
}

// View PDF in browser
public function viewPDF($id)
{
    $data = Model::findOrFail($id);
    $pdf = Pdf::loadView('path.to.pdf.template', compact('data'));
    return $pdf->stream('filename.pdf');
}
```

## Available Components

### Sections
- `.section` - Main content section
- `.section-title` - Section heading
- `.info-grid` - Information grid layout
- `.info-row` - Information row
- `.info-label` - Label for information
- `.info-value` - Value for information

### Tables
- `.data-table` - Styled data table
- Table headers with blue background
- Hover effects on rows

### Summary Boxes
- `.summary-box` - Summary/calculation box
- `.summary-row` - Summary row
- `.summary-label` - Summary label
- `.summary-value` - Summary value
- `.total-row` - Highlighted total row

### Special Sections
- `.notes-section` - Notes with yellow background
- `.terms-section` - Terms & conditions section
- `.validity-info` - Validity information box

### Status Badges
- `.status-badge` - Status badge
- `.status-pending` - Pending status
- `.status-sent` - Sent status
- `.status-accepted` - Accepted status
- `.status-rejected` - Rejected status
- `.status-expired` - Expired status

## Styling Guidelines

1. **Colors:**
   - Primary: #2563eb (Blue)
   - Success: #059669 (Green)
   - Danger: #dc2626 (Red)
   - Warning: #d97706 (Orange)

2. **Fonts:**
   - Primary: DejaVu Sans (for PDF compatibility)
   - Fallback: Arial, Helvetica, sans-serif

3. **Spacing:**
   - Use utility classes: `.mb-10`, `.mb-15`, `.mb-20`, `.mt-10`, etc.
   - Consistent padding: 10px, 15px, 20px

4. **Layout:**
   - Max width: 210mm (A4)
   - Padding: 20mm
   - Sections: 20px margin-bottom

## Best Practices

1. **Always use the base layout** for consistency
2. **Use semantic HTML** for better PDF rendering
3. **Test PDFs** in different PDF viewers
4. **Keep content concise** - PDFs should be printable
5. **Use tables** for structured data
6. **Include all necessary information** but avoid clutter
7. **Use status badges** for visual status indication
8. **Include contact information** in footer

## Examples

See `admin/quotations/pdf.blade.php` for a complete example of a quotation PDF.

See `invoice-example.blade.php` for an example of an invoice PDF.




