// import { isInViewport } from "../utils";
// import lottie from 'lottie-web';

// export default class productBlock {
//   constructor() {
//     this.lottieInstances = new Map();
//     this.init();
//   }

//   init = () => {
//     this.setDomMap();
//     this.bindEvents();
//   };

//   setDomMap = () => {
//     this.containers = $(".products-container");
//     this.lottieEls = $(".hover-animation"); // assumes each container has .hover-animation placeholder
//   };

//   bindEvents = () => {
//     const self = this;

//     // Load all Lottie animations and store instances
//     this.lottieEls.each(function () {
//       const el = this;
//       const path = el.dataset.lottiePath;

//       const anim = lottie.loadAnimation({
//         container: el,
//         renderer: "svg",
//         loop: true,
//         autoplay: false,
//         path: path, // e.g., /wp-content/themes/your-theme/assets/lottie/product1.json
//       });

//       self.lottieInstances.set(el, anim);
//     });

//     // First product active logic
//     this.containers.each(function () {
//       const container = $(this);
//       const products = container.find(".products");

//       const firstProduct = products.first();
//       firstProduct.addClass("active");

//       const firstLottie = firstProduct.find(".hover-animation").get(0);
//       const instance = self.lottieInstances.get(firstLottie);
//       if (instance) instance.play();

//       products.on("click", function () {
//         const clickedProduct = $(this);
//         if (clickedProduct.hasClass("active")) return;

//         clickedProduct.addClass("active").siblings().removeClass("active");

//         const newEl = clickedProduct.find(".hover-animation").get(0);
//         const newAnim = self.lottieInstances.get(newEl);
//         if (newAnim) newAnim.play();

//         products.each(function () {
//           const product = $(this);
//           const el = product.find(".hover-animation").get(0);
//           const anim = self.lottieInstances.get(el);
//           if (!product.hasClass("active") && anim) {
//             anim.goToAndStop(0, true); // reset to beginning
//           }
//         });
//       });
//     });

//     // Auto play/pause on scroll using isInViewport
//     $(window).on("scroll load", () => {
//       self.lottieEls.each((i, el) => {
//         const instance = self.lottieInstances.get(el);
//         if (!instance) return;

//         if (isInViewport($(el))) {
//           instance.play();
//         } else {
//           instance.pause();
//         }
//       });
//     });
//   };
// }



import { isInViewport } from "../utils";


export default class ProductBlock {
  constructor() {
    this.init();
  }

  init = () => {
    this.setDomMap();
    this.bindEvents();
  };

  setDomMap = () => {
    // All product containers
    this.containers = document.querySelectorAll(".products-container");
  };

  bindEvents = () => {
    this.containers.forEach((container) => {
      const products = container.querySelectorAll(".products");

      // Set first product active
      const firstProduct = products[0];
      if (firstProduct) {
        firstProduct.classList.add("active");
      }

      // Click handler for each product
      products.forEach((product) => {
        product.addEventListener("click", () => {
          if (product.classList.contains("active")) return;

          // Remove "active" from siblings and set on clicked
          products.forEach((p) => p.classList.remove("active"));
          product.classList.add("active");
        });
      });
    });
  };
}
