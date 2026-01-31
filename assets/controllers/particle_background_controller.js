import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.canvas = document.createElement('canvas');
        this.element.appendChild(this.canvas);
        this.ctx = this.canvas.getContext('2d');

        this.resize();
        window.addEventListener('resize', this.resize.bind(this));

        this.particles = [];
        this.particleCount = 100; // Adjustable

        this.initParticles();
        this.animate();
    }

    disconnect() {
        window.removeEventListener('resize', this.resize.bind(this));
        cancelAnimationFrame(this.animationFrame);
    }

    resize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.canvas.width = this.width;
        this.canvas.height = this.height;
    }

    initParticles() {
        this.particles = [];
        for (let i = 0; i < this.particleCount; i++) {
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                vx: Math.random() * 2 - 1, // Velocity X
                vy: Math.random() * 2 - 1, // Velocity Y
                size: Math.random() * 2 + 1
            });
        }
    }

    animate() {
        this.ctx.clearRect(0, 0, this.width, this.height);
        this.ctx.fillStyle = 'rgba(100, 100, 255, 0.5)'; // Light blueish, adjustable

        this.particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;

            // Bounce off edges
            if (p.x < 0 || p.x > this.width) p.vx *= -1;
            if (p.y < 0 || p.y > this.height) p.vy *= -1;

            this.ctx.beginPath();
            this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            this.ctx.fill();
        });

        // Simple wave connection logic (optional, for "wave" effect)
        // Trying to mimic a "net" or "wave" might be complex without looking at the exact CodePen
        // But the user asked for "truc qui bouge" (thing that moves). 
        // I'll stick to a simple floating particles for now, maybe add lines between close particles for a "constellation" effect which is popular.
        this.connectParticles();

        this.animationFrame = requestAnimationFrame(this.animate.bind(this));
    }

    connectParticles() {
        for (let i = 0; i < this.particles.length; i++) {
            for (let j = i + 1; j < this.particles.length; j++) {
                const dx = this.particles[i].x - this.particles[j].x;
                const dy = this.particles[i].y - this.particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 100) {
                    this.ctx.strokeStyle = `rgba(100, 100, 255, ${1 - dist / 100})`;
                    this.ctx.lineWidth = 0.5;
                    this.ctx.beginPath();
                    this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
                    this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
                    this.ctx.stroke();
                }
            }
        }
    }
}
