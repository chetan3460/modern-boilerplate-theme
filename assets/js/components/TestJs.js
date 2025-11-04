export default class TestJs {
  constructor() {
    this.blockEl = document.querySelector('[data-component="TestJs"]');
    
    if (!this.blockEl) return;
    
    this.init();
  }
  
  init() {
    // Initialize your component here
    console.log('TestJs initialized');
  }
}
