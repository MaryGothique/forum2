class Diaporama {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.btnPrevious = this.container.querySelector(".previous");
        this.btnNext = this.container.querySelector(".next");
        this.images = Array.from(this.container.querySelectorAll("figure"));
        this.index = 0;
        this.show(); 
        this.btnNext.addEventListener("click", () => { this.next() });
        this.btnPrevious.addEventListener("click", () => { this.previous() });
    }

    next() {
        this.index = (this.index + 1) % this.images.length;
        this.show(); 
    }

    previous() {
        this.index = (this.index - 1 + this.images.length) % this.images.length;
        this.show(); 
    }

    show() { 
        this.images.forEach(image => image.style.display = 'none');
        this.images[this.index].style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const diaporama = new Diaporama("container");
  

});
