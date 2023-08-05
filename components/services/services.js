document.addEventListener('DOMContentLoaded', function() {
    // Get all elements with the 'download-link' class
    const downloadLinks = document.querySelectorAll('.download-link');

    // Attach click event listener to each download link
    downloadLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Get the filename from the data-filename attribute
            const filename = this.getAttribute('data-filename');

            // Update the PDF file URL with the correct path
            const pdfUrl = '/assets/files/pages/services/pdfs/' + filename;

            // Create an anchor element and trigger the download
            const anchor = document.createElement('a');
            anchor.href = pdfUrl;
            anchor.download = filename;
            anchor.click();
        });
    });
});
