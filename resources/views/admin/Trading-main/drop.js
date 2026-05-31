

document.querySelectorAll('.dropbtn').forEach(dropbtn => {
   dropbtn.addEventListener('click', (event) => {
       event.preventDefault(); // Prevent any default action (if any)
       const targetId = dropbtn.getAttribute('data-target');
       document.getElementById(targetId).classList.toggle('show');
   });
});