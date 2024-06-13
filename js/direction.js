const buttons = document.querySelectorAll('.custom-btn');
const imageContents = document.querySelectorAll('.image-content');

buttons.forEach((button) => {
  button.addEventListener('click', () => {
    const targetId = button.getAttribute('data-target');
    const targetImageContent = document.querySelector(targetId);

    imageContents.forEach((imageContent) => {
      imageContent.style.display = 'none';
    });

    targetImageContent.style.display = 'block';
  });
});