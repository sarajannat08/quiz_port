document.addEventListener("DOMContentLoaded", () => {
  const animatedElements = document.querySelectorAll('.animate-slide-up, .animate-fade-in');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  animatedElements.forEach(el => observer.observe(el));
});
document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".animate-on-scroll");

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(el => observer.observe(el));
});


document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".reply-toggle").forEach(button => {
    button.addEventListener("click", () => {
      const form = document.getElementById("reply-form-" + button.dataset.id);
      if (form.style.display === "none") {
        form.style.display = "block";
        form.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        form.style.display = "none";
      }
    });
  });

  const animated = document.querySelectorAll(".animate-on-scroll");
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  }, { threshold: 0.1 });

  animated.forEach(el => observer.observe(el));
});


document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".reply-toggle").forEach(button => {
    button.addEventListener("click", () => {
      const form = document.getElementById("reply-form-" + button.dataset.id);
      form.style.display = form.style.display === "none" ? "block" : "none";
      if (form.style.display === "block") {
        form.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    });
  });

  const animated = document.querySelectorAll(".animate-on-scroll");
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  }, { threshold: 0.1 });

  animated.forEach(el => observer.observe(el));
});


