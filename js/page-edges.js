// фиксированные тёмные края страницы — сливаются с футером #2D2D2D
var canvas = document.getElementById('edge-canvas');

if (canvas) {
    var ctx = canvas.getContext('2d');

    var EDGE_W = 180;
    // цвет тёмного края — совпадает с --primary-dark (#2D2D2D)
    var R = 45, G = 45, B = 45;
    // цвет частиц — СВЕТЛЕЕ тёмного края, чтобы создавать видимое движение
    var PR = 110, PG = 108, PB = 112;

    var pts = [];

    function initParticles() {
        pts = [];
        var count = 7;
        for (var i = 0; i < count; i++) {
            // левый край
            pts.push({
                x: Math.random() * (EDGE_W * 0.75),
                y: Math.random() * canvas.height,
                r: 80 + Math.random() * 90,
                dy: (Math.random() > 0.5 ? 1 : -1) * (0.2 + Math.random() * 0.35),
                a: 0.18 + Math.random() * 0.14
            });
            // правый край
            pts.push({
                x: canvas.width - Math.random() * (EDGE_W * 0.75),
                y: Math.random() * canvas.height,
                r: 80 + Math.random() * 90,
                dy: (Math.random() > 0.5 ? 1 : -1) * (0.2 + Math.random() * 0.35),
                a: 0.18 + Math.random() * 0.14
            });
        }
    }

    function resizeEdge() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        initParticles();
    }

    resizeEdge();
    window.addEventListener('resize', resizeEdge);

    function drawEdge() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // левая полоса: #2D2D2D → transparent
        var gL = ctx.createLinearGradient(0, 0, EDGE_W, 0);
        gL.addColorStop(0,    'rgba(' + R + ',' + G + ',' + B + ',0.94)');
        gL.addColorStop(0.5,  'rgba(' + R + ',' + G + ',' + B + ',0.38)');
        gL.addColorStop(1,    'rgba(' + R + ',' + G + ',' + B + ',0)');
        ctx.fillStyle = gL;
        ctx.fillRect(0, 0, EDGE_W, canvas.height);

        // правая полоса: #2D2D2D → transparent
        var gR = ctx.createLinearGradient(canvas.width, 0, canvas.width - EDGE_W, 0);
        gR.addColorStop(0,    'rgba(' + R + ',' + G + ',' + B + ',0.94)');
        gR.addColorStop(0.5,  'rgba(' + R + ',' + G + ',' + B + ',0.38)');
        gR.addColorStop(1,    'rgba(' + R + ',' + G + ',' + B + ',0)');
        ctx.fillStyle = gR;
        ctx.fillRect(canvas.width - EDGE_W, 0, EDGE_W, canvas.height);

        // туманные блобы — светлее тёмного края, двигаются вертикально
        for (var i = 0; i < pts.length; i++) {
            var p = pts[i];
            var grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
            grad.addColorStop(0,   'rgba(' + PR + ',' + PG + ',' + PB + ',' + p.a + ')');
            grad.addColorStop(0.5, 'rgba(' + PR + ',' + PG + ',' + PB + ',' + (p.a * 0.4) + ')');
            grad.addColorStop(1,   'rgba(' + PR + ',' + PG + ',' + PB + ',0)');
            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();

            p.y += p.dy;
            if (p.y < -p.r) p.y = canvas.height + p.r;
            if (p.y > canvas.height + p.r) p.y = -p.r;
        }

        requestAnimationFrame(drawEdge);
    }

    drawEdge();
}
