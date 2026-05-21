const sessionKey = 'papi_instruksi_shown_{{ session("test_session_id") }}';

// Cek apakah popup sudah pernah ditutup di sesi ini
if (sessionStorage.getItem(sessionKey)) {
    // Sudah pernah tampil — langsung tampilkan konten tes
    document.getElementById("popupInstruksi").style.display = "none";
    document.getElementById("tesContent").style.display = "block";
} else {
    // Belum pernah — tampilkan popup dan mulai countdown
    let countdown = 10;

    const countTimer = setInterval(() => {
        countdown--;
        const num = document.getElementById("countdownNum");
        const btn = document.getElementById("btnMulai");
        const btnTxt = document.getElementById("btnMulaiText");
        const badge = document.getElementById("countdownBadge");

        if (num) num.textContent = countdown;
        if (btnTxt) btnTxt.textContent = `Siap dalam ${countdown} detik...`;

        if (countdown <= 0) {
            clearInterval(countTimer);
            if (btn) btn.disabled = false;
            if (btnTxt) btnTxt.textContent = "Mulai Tes Sekarang!";
            if (badge)
                badge.innerHTML =
                    '<i class="bi bi-check-circle me-1"></i>Siap!';
        }
    }, 1000);
}

function tutupPopup() {
    // Tandai bahwa popup sudah ditampilkan untuk sesi ini
    sessionStorage.setItem(sessionKey, "1");

    const popup = document.getElementById("popupInstruksi");
    const konten = document.getElementById("tesContent");

    popup.style.transition = "opacity 0.25s ease";
    popup.style.opacity = "0";

    setTimeout(() => {
        popup.style.display = "none";
        konten.style.display = "block";

        // Fokus ke option pertama
        const firstLabel = konten.querySelector(".option-label");
        if (firstLabel) firstLabel.focus();
    }, 250);
}
