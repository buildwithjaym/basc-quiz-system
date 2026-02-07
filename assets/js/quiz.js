// assets/js/quiz.js
(() => {
    const page = (location.pathname.split("/").pop() || "").toLowerCase();
    if (page !== "quiz.php") return;

    const payload = window.QUIZ_PAYLOAD || null;
    if (!payload) return;

    const root = document.getElementById("quizRoot");
    const qGrid = document.getElementById("qGrid");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const submitBtn = document.getElementById("submitBtn");
    const counter = document.getElementById("qCounter");
    const timerEl = document.getElementById("timer");
    const progressBar = document.getElementById("progressBar");

    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modalTitle");
    const modalMsg = document.getElementById("modalMsg");
    const modalEmoji = document.getElementById("modalEmoji");
    const modalBtn = document.getElementById("modalBtn");

    const STORAGE_KEY = "basc_exam_answers_v1";

    let submitted = false;

    function showToast({ emoji = "✨", title = "Nice!", msg = "", duration = 2200 } = {}) {
        let el = document.querySelector(".toast");
        if (!el) {
            el = document.createElement("div");
            el.className = "toast";
            el.innerHTML = `
        <div class="toast__emoji"></div>
        <div>
          <div class="toast__title"></div>
          <div class="toast__msg"></div>
        </div>
      `;
            document.body.appendChild(el);
        }
        el.querySelector(".toast__emoji").textContent = emoji;
        el.querySelector(".toast__title").textContent = title;
        el.querySelector(".toast__msg").textContent = msg;
        el.classList.add("is-show");
        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => el.classList.remove("is-show"), duration);
    }

    function openModal({ emoji = "🎉", title = "Submitted!", msg = "Nice work!" } = {}) {
        if (!modal) return;
        if (modalEmoji) modalEmoji.textContent = emoji;
        if (modalTitle) modalTitle.textContent = title;
        if (modalMsg) modalMsg.textContent = msg;
        modal.setAttribute("aria-hidden", "false");
        modal.classList.add("is-open");
    }

    const NAV_LOCK = (() => {
        let locked = true;

        function abs(href) {
            try { return new URL(href, location.href).href; } catch { return null; }
        }

        function shouldBlock(targetHref) {
            if (!locked || submitted) return false;
            const t = abs(targetHref);
            if (!t) return false;
            const here = location.href;
            if (t === here) return false;
            return true;
        }

        function warn() {
            showToast({ emoji: "🔒", title: "Restricted", msg: "You can’t leave this page right now.", duration: 1800 });
        }

        function start() {
            locked = true;

            try {
                history.pushState({ __lock: 1 }, "", location.href);
                addEventListener("popstate", () => {
                    if (!locked || submitted) return;
                    try { history.pushState({ __lock: 1 }, "", location.href); } catch { }
                    warn();
                });
            } catch { }

            document.addEventListener("click", (e) => {
                if (!locked || submitted) return;

                const a = e.target && e.target.closest ? e.target.closest("a[href]") : null;
                if (!a) return;

                const href = a.getAttribute("href") || "";
                if (!href.trim() || href === "#" || href.startsWith("javascript:")) return;

                if (shouldBlock(a.href)) {
                    e.preventDefault();
                    e.stopPropagation();
                    warn();
                }
            }, true);

            document.addEventListener("submit", (e) => {
                if (!locked || submitted) return;

                const form = e.target;
                if (!form || !(form instanceof HTMLFormElement)) return;

                const actionAttr = (form.getAttribute("action") || "").trim();
                const action = actionAttr ? abs(actionAttr) : location.href;

                if (action && shouldBlock(action)) {
                    e.preventDefault();
                    e.stopPropagation();
                    showToast({ emoji: "🔒", title: "Restricted", msg: "Navigation is locked during the quiz.", duration: 1800 });
                }
            }, true);

            addEventListener("beforeunload", (e) => {
                if (!locked || submitted) return;
                e.preventDefault();
                e.returnValue = "";
            });
        }

        function unlock() { locked = false; }

        return { start, unlock };
    })();

    const bank = [
        ...payload.mcq.map(q => ({ ...q, type: "mcq" })),
        ...payload.ident.map(q => ({ ...q, type: "ident" })),
    ];

    let answers = {};
    try { answers = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}") || {}; } catch { answers = {}; }

    let index = 0;
    let timeLeft = Number(payload.maxTimeSeconds || 0);

    const clamp = (n, a, b) => Math.max(a, Math.min(b, n));
    const pad2 = n => String(n).padStart(2, "0");
    const fmtTime = secs => {
        secs = Math.max(0, secs | 0);
        const m = Math.floor(secs / 60);
        const s = secs % 60;
        return `${pad2(m)}:${pad2(s)}`;
    };

    const save = () => localStorage.setItem(STORAGE_KEY, JSON.stringify(answers));

    const isAnswered = (q) => {
        const v = answers[q.key];
        if (q.type === "mcq") return v === "A" || v === "B" || v === "C" || v === "D";
        return typeof v === "string" && v.trim().length > 0;
    };

    const answeredCount = () => bank.reduce((acc, q) => acc + (isAnswered(q) ? 1 : 0), 0);

    const updateProgress = () => {
        const done = answeredCount();
        const pct = (done / bank.length) * 100;
        if (progressBar) progressBar.style.width = `${pct}%`;
        if (submitBtn) submitBtn.disabled = done !== bank.length || submitted;
    };

    const updateNav = () => {
        if (!qGrid) return;
        [...qGrid.children].forEach((btn, i) => {
            const q = bank[i];
            btn.classList.toggle("is-active", i === index);
            btn.classList.toggle("is-done", isAnswered(q));
        });
    };

    const setCounter = () => {
        if (!counter) return;
        counter.textContent = `Question ${index + 1} / ${bank.length}`;
    };

    const setButtons = () => {
        if (prevBtn) prevBtn.disabled = index === 0 || submitted;
        if (nextBtn) nextBtn.disabled = index === bank.length - 1 || submitted;
    };

    function escapeHtml(s) {
        return String(s)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }
    function escapeAttr(s) {
        return escapeHtml(s).replaceAll("`", "&#096;");
    }

    const renderMCQ = (q, num) => {
        const picked = answers[q.key] || "";
        const choices = q.choices || {};

        const wrap = document.createElement("div");
        wrap.className = "qcard";
        wrap.innerHTML = `
      <div class="qmeta">
        <span class="tag">MCQ</span>
        <span class="tag">#${num}</span>
      </div>
      <div class="qtitle">${escapeHtml(q.prompt || "")}</div>
      <div class="choices" role="radiogroup"></div>
    `;

        const choicesRoot = wrap.querySelector(".choices");

        ["A", "B", "C", "D"].forEach(letter => {
            const item = document.createElement("label");
            item.className = "choice";
            if (picked === letter) item.classList.add("is-picked");

            item.innerHTML = `
        <input type="radio" name="${q.key}" value="${letter}" ${picked === letter ? "checked" : ""} />
        <div>
          <div style="font-weight:900">${letter}.</div>
          <div class="muted">${escapeHtml(String(choices[letter] || ""))}</div>
        </div>
      `;

            item.addEventListener("click", () => {
                if (submitted) return;
                answers[q.key] = letter;
                save();
                [...choicesRoot.children].forEach(c => c.classList.remove("is-picked"));
                item.classList.add("is-picked");
                updateNav();
                updateProgress();
            });

            choicesRoot.appendChild(item);
        });

        return wrap;
    };

    const renderIdent = (q, num) => {
        const val = typeof answers[q.key] === "string" ? answers[q.key] : "";

        const wrap = document.createElement("div");
        wrap.className = "qcard";
        wrap.innerHTML = `
      <div class="qmeta">
        <span class="tag">IDENTIFICATION</span>
        <span class="tag">#${num}</span>
      </div>
      <div class="qtitle">Type the tool/logo name</div>
      <div class="identWrap">
        <div class="identImg">
          <img src="${escapeAttr(q.image || "")}" alt="Identification image" />
        </div>
        <div class="identInput">
          <div class="muted small" style="margin-bottom:8px;">Type your answer (example: github)</div>
          <input id="identInput" type="text" placeholder="Type your answer..." value="${escapeAttr(val)}" autocomplete="off" />
        </div>
      </div>
    `;

        const input = wrap.querySelector("#identInput");
        input.addEventListener("input", () => {
            if (submitted) return;
            answers[q.key] = input.value;
            save();
            updateNav();
            updateProgress();
        });

        setTimeout(() => input.focus(), 0);
        return wrap;
    };

    const render = () => {
        const q = bank[index];
        if (!root) return;
        root.innerHTML = "";
        root.appendChild(q.type === "mcq" ? renderMCQ(q, index + 1) : renderIdent(q, index + 1));
        setCounter();
        setButtons();
        updateNav();
        updateProgress();
    };

    const buildNav = () => {
        if (!qGrid) return;
        qGrid.innerHTML = "";
        for (let i = 0; i < bank.length; i++) {
            const b = document.createElement("button");
            b.type = "button";
            b.className = "qbtn";
            b.textContent = String(i + 1);
            b.addEventListener("click", () => {
                if (submitted) return;
                index = i;
                render();
            });
            qGrid.appendChild(b);
        }
        updateNav();
    };

    const tick = () => {
        if (submitted) return;
        timeLeft = clamp(timeLeft - 1, 0, 999999);
        if (timerEl) timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 0) doSubmit(true);
    };

    async function doSubmit(auto = false) {
        if (submitted) return;
        if (!auto && answeredCount() !== bank.length) return;

        submitted = true;
        if (submitBtn) submitBtn.disabled = true;
        if (prevBtn) prevBtn.disabled = true;
        if (nextBtn) nextBtn.disabled = true;

        const clean = {};
        bank.forEach(q => {
            const v = answers[q.key];
            if (q.type === "mcq") {
                if (v === "A" || v === "B" || v === "C" || v === "D") clean[q.key] = v;
            } else {
                if (typeof v === "string") clean[q.key] = v.trim();
            }
        });

        const usedSeconds = Math.max(0, (payload.maxTimeSeconds || 0) - timeLeft);

        try {
            const res = await fetch("submit.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ time_seconds: usedSeconds, answers: clean })
            });

            const data = await res.json().catch(() => null);
            if (!res.ok || !data || data.ok !== true) {
                submitted = false;
                setButtons();
                updateProgress();
                showToast({ emoji: "⚠️", title: "Submit failed", msg: (data && data.error) ? data.error : "Server error. Try again.", duration: 2600 });
                return;
            }

            localStorage.removeItem(STORAGE_KEY);
            NAV_LOCK.unlock();

            showToast({ emoji: auto ? "⏰" : "✨", title: auto ? "Time's up!" : "Submitted!", msg: data.compliment || "Nice work!", duration: 2400 });
            openModal({ emoji: "🎉", title: "Submitted!", msg: data.compliment || "Nice work!" });

            const go = () => { location.href = "result.php"; };
            if (modalBtn) modalBtn.onclick = go;
            else setTimeout(go, 900);

        } catch {
            submitted = false;
            setButtons();
            updateProgress();
            showToast({ emoji: "📶", title: "Network error", msg: "Please try again.", duration: 2600 });
        }
    }

    if (prevBtn) prevBtn.addEventListener("click", () => {
        if (submitted) return;
        index = clamp(index - 1, 0, bank.length - 1);
        render();
    });

    if (nextBtn) nextBtn.addEventListener("click", () => {
        if (submitted) return;
        index = clamp(index + 1, 0, bank.length - 1);
        render();
    });

    if (submitBtn) submitBtn.addEventListener("click", () => {
        if (submitted) return;
        if (answeredCount() !== bank.length) {
            showToast({ emoji: "📝", title: "Incomplete", msg: "Answer all questions before submitting.", duration: 2200 });
            return;
        }
        doSubmit(false);
    });

    if (timerEl) timerEl.textContent = fmtTime(timeLeft);
    buildNav();
    render();
    updateProgress();
    setInterval(tick, 1000);

    (function tabRestrict() {
        let tabChangeCount = 0;
        const maxTabChanges = 3;

        document.addEventListener("visibilitychange", () => {
            if (submitted) return;
            if (!document.hidden) return;

            tabChangeCount++;
            if (tabChangeCount >= maxTabChanges) {
                alert("⚠ You have switched tabs too many times. Access is now restricted!");
                fetch("restrict_attempt.php", { method: "POST" }).finally(() => {
                    location.href = "restricted.php";
                });
            }
            else {
                showToast({ emoji: "⚠️", title: "Warning", msg: `Tab switches: ${tabChangeCount}/${maxTabChanges}`, duration: 2000 });
            }
        });
    })();

    NAV_LOCK.start();
})();
