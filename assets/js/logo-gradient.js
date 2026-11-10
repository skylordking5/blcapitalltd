// Apply gradient to logo text
document.addEventListener('DOMContentLoaded', function() {
  const logoH1 = document.querySelector('.header .logo h1');
  if (logoH1) {
    logoH1.style.background = 'linear-gradient(90deg, #1E3CFF, #2A4BFF, #2F7BFF, #00BFFF, #33CFFF)';
    logoH1.style.webkitBackgroundClip = 'text';
    logoH1.style.webkitTextFillColor = 'transparent';
    logoH1.style.backgroundClip = 'text';
  }
});
