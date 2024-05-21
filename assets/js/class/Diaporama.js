// This class represents a Diaporama component for displaying a slideshow of images

export class Diaporama {
    // Constructor method initializes the Diaporama object
    constructor(containerId) {
        // Get the container element by its ID
        this.container = document.getElementById(containerId);
        // Get the previous and next buttons within the container
        this.btnPrevious = this.container.querySelector(".previous");
        this.btnNext = this.container.querySelector(".next");
        // Get all images within the container and convert them into an array
        this.images = Array.from(this.container.querySelectorAll("figure"));
        // Initialize the index of the currently displayed image
        this.index = 0;
        // Display the initial image
        this.show(); 
        // Add event listeners for the next and previous buttons
        this.btnNext.addEventListener("click", () => { this.next() });
        this.btnPrevious.addEventListener("click", () => { this.previous() });
    }

    // Method to display the next image
    next() {
        // Increment the index and wrap around if it exceeds the length of the images array
        this.index = (this.index + 1) % this.images.length;
        // Display the image at the new index
        this.show(); 
    }

    // Method to display the previous image
    previous() {
        // Decrement the index and wrap around if it becomes negative
        this.index = (this.index - 1 + this.images.length) % this.images.length;
        // Display the image at the new index
        this.show(); 
    }

    // Method to display the current image
    show() { 
        // Hide all images
        this.images.forEach(image => image.style.display = 'none');
        // Display the image at the current index
        this.images[this.index].style.display = 'block';
    }
}
