(function () {
  "use strict";

  // ----- Year in footer -----
  const yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  // ----- Navbar shadow on scroll -----
  const nav = document.getElementById("mainNav");
  const onScrollNav = () => {
    if (!nav) return;
    nav.classList.toggle("scrolled", window.scrollY > 30);
  };
  document.addEventListener("scroll", onScrollNav, { passive: true });
  onScrollNav();

  // ----- Back to top -----
  const backToTop = document.getElementById("backToTop");
  const onScrollBtt = () => {
    if (!backToTop) return;
    backToTop.classList.toggle("visible", window.scrollY > 400);
  };
  document.addEventListener("scroll", onScrollBtt, { passive: true });
  if (backToTop) {
    backToTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // ----- Animated counters -----
  const counters = document.querySelectorAll("[data-count]");
  const animateCount = (el) => {
    const target = parseInt(el.dataset.count, 10);
    const duration = 1100;
    const start = performance.now();
    const tick = (now) => {
      const t = Math.min((now - start) / duration, 1);
      // ease-out cubic
      const eased = 1 - Math.pow(1 - t, 3);
      el.textContent = Math.round(eased * target);
      if (t < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  if ("IntersectionObserver" in window && counters.length) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCount(entry.target);
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.4 }
    );
    counters.forEach((c) => io.observe(c));
  } else {
    counters.forEach(animateCount);
  }

  // ----- Reveal on scroll -----
  const revealTargets = document.querySelectorAll(
    ".timeline-item, .talk-card, .about-card, .about-feature, .closing-tile, .stat-card"
  );
  revealTargets.forEach((el) => el.classList.add("reveal"));

  if ("IntersectionObserver" in window) {
    const io2 = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            io2.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    revealTargets.forEach((el) => io2.observe(el));
  } else {
    revealTargets.forEach((el) => el.classList.add("visible"));
  }

  // ----- Filter & search for talks -----
  const searchInput = document.getElementById("searchInput");
  const chips = document.querySelectorAll("#batteryChips .chip");
  const talkCols = document.querySelectorAll(".talk-col");
  const batteryBlocks = document.querySelectorAll(".battery-block");
  const resultCount = document.getElementById("resultCount");
  const emptyState = document.getElementById("emptyState");

  let activeFilter = "all";
  let activeQuery = "";

  const applyFilter = () => {
    let visible = 0;

    talkCols.forEach((col) => {
      const battery = col.dataset.battery;
      const text = col.textContent.toLowerCase();
      const matchesBattery = activeFilter === "all" || activeFilter === battery;
      const matchesQuery =
        !activeQuery || text.includes(activeQuery.toLowerCase());
      const show = matchesBattery && matchesQuery;
      col.style.display = show ? "" : "none";
      if (show) visible += 1;
    });

    // hide empty battery blocks (when filter active or query has no matches there)
    batteryBlocks.forEach((block) => {
      const battery = block.dataset.battery;
      const blockMatchesFilter = activeFilter === "all" || activeFilter === battery;

      if (!blockMatchesFilter) {
        block.style.display = "none";
        return;
      }

      const visibleInBlock = block.querySelectorAll(
        '.talk-col:not([style*="display: none"])'
      );
      block.style.display = visibleInBlock.length > 0 ? "" : "none";
    });

    if (resultCount) resultCount.textContent = visible;
    if (emptyState) emptyState.classList.toggle("d-none", visible !== 0);
  };

  if (searchInput) {
    let debounce;
    searchInput.addEventListener("input", (e) => {
      clearTimeout(debounce);
      debounce = setTimeout(() => {
        activeQuery = e.target.value.trim();
        applyFilter();
      }, 120);
    });
  }

  chips.forEach((chip) => {
    chip.addEventListener("click", () => {
      chips.forEach((c) => c.classList.remove("active"));
      chip.classList.add("active");
      activeFilter = chip.dataset.filter;
      applyFilter();

      // soft scroll into view of comunicacoes section
      const target = document.getElementById("comunicacoes");
      if (target && window.scrollY > target.offsetTop + 200) {
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });

  // close mobile menu after click
  const navLinks = document.querySelectorAll("#navMenu .nav-link, #navMenu .btn-cta");
  const navCollapse = document.getElementById("navMenu");
  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (navCollapse && navCollapse.classList.contains("show")) {
        const bs = bootstrap.Collapse.getInstance(navCollapse);
        if (bs) bs.hide();
      }
    });
  });
})();
