const menuToggle = document.querySelector(".menu-toggle");
const siteNav = document.querySelector(".site-nav");
const navLinks = document.querySelectorAll(".site-nav a");
const revealItems = document.querySelectorAll(".reveal, .reveal-delay");
const contactForm = document.getElementById("contact-form");
const formFeedback = document.getElementById("form-feedback");

// Controla a abertura do menu mobile.
if (menuToggle && siteNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = siteNav.classList.toggle("is-open");
    menuToggle.classList.toggle("is-active", isOpen);
    menuToggle.setAttribute("aria-expanded", String(isOpen));
  });

  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      siteNav.classList.remove("is-open");
      menuToggle.classList.remove("is-active");
      menuToggle.setAttribute("aria-expanded", "false");
    });
  });
}

// Ativa animações suaves quando os blocos entram no ecrã.
if ("IntersectionObserver" in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  revealItems.forEach((item) => observer.observe(item));
} else {
  revealItems.forEach((item) => item.classList.add("is-visible"));
}

// Validação frontend simples para feedback imediato ao utilizador.
if (contactForm && formFeedback) {
  contactForm.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!contactForm.checkValidity()) {
      formFeedback.textContent = "Preencha todos os campos obrigatórios corretamente.";
      formFeedback.className = "form-feedback is-error";
      return;
    }

    formFeedback.textContent = "Pedido enviado com sucesso. Entraremos em contacto brevemente.";
    formFeedback.className = "form-feedback is-success";
    contactForm.reset();
  });
}
