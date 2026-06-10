// анимация левой панели страниц входа и регистрации
var canvas = document.getElementById('shadow-canvas');

if (canvas) {
    var ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // частицы чуть светлее фона #0c0c0e — едва видимые туманные пятна
    var pts = [];
    var NUM_PARTICLES = 8;

    for (var i = 0; i < NUM_PARTICLES; i++) {
        pts.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: 130 + Math.random() * 160,
            dx: (Math.random() - 0.5) * 0.18,
            dy: (Math.random() - 0.5) * 0.12,
            a: 0.04 + Math.random() * 0.05
        });
    }

    function drawAuth() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (var i = 0; i < pts.length; i++) {
            var p = pts[i];
            var grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
            grad.addColorStop(0, 'rgba(160, 158, 165, ' + p.a + ')');
            grad.addColorStop(0.5, 'rgba(130, 128, 135, ' + (p.a * 0.4) + ')');
            grad.addColorStop(1, 'rgba(100, 98, 105, 0)');

            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();

            p.x += p.dx;
            p.y += p.dy;

            if (p.x < -p.r) p.x = canvas.width + p.r;
            if (p.x > canvas.width + p.r) p.x = -p.r;
            if (p.y < -p.r) p.y = canvas.height + p.r;
            if (p.y > canvas.height + p.r) p.y = -p.r;
        }

        requestAnimationFrame(drawAuth);
    }

    drawAuth();
}
