export default class ServiceListBlock {
  constructor() {
    this.blockEl = document.querySelector('[data-component="ServiceListBlock"]');
    
    if (!this.blockEl) return;
    
    this.init();
  }
  
  init() {
    // Initialize your component here
    console.log('ServiceListBlock initialized');
  }
}
