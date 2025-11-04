export default class TestBlcok {
  constructor() {
    this.blockEl = document.querySelector('[data-component="TestBlcok"]');
    
    if (!this.blockEl) return;
    
    this.init();
  }
  
  init() {
    // Initialize your component here
    console.log('TestBlcok initialized');
  }
}
