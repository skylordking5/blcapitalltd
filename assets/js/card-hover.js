// Card Hover Effects for Mission, Vision & Values
document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.mission-card, .vision-card, .values-card');
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-15px)';
      this.style.boxShadow = this.classList.contains('mission-card') 
        ? '0px 25px 60px rgba(220, 53, 69, 0.3)' 
        : this.classList.contains('vision-card')
        ? '0px 25px 60px rgba(13, 110, 253, 0.3)'
        : '0px 25px 60px rgba(25, 135, 84, 0.3)';
      this.style.background = this.classList.contains('mission-card')
        ? 'linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(255, 255, 255, 1) 100%)'
        : this.classList.contains('vision-card')
        ? 'linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(255, 255, 255, 1) 100%)'
        : 'linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(255, 255, 255, 1) 100%)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '';
      this.style.background = '';
    });
  });
});
