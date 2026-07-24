<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SHARK GPT</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background:
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(59, 130, 246, 0.15), transparent),
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(139, 92, 246, 0.1), transparent),
                radial-gradient(ellipse 50% 30% at 40% 80%, rgba(6, 182, 212, 0.1), transparent),
                #0a0a0a;
        }

        .bg-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 3rem;
            position: relative;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo:hover .logo-icon {
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
        }

        .logo img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .logo:hover img {
            transform: rotate(10deg) scale(1.1);
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
            transition: color 0.3s ease;
        }

        .logo:hover .logo-text {
            color: #60a5fa;
        }

        .nav-buttons {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-ghost {
            color: #a1a1aa;
            background: transparent;
        }

        .btn-ghost:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-ghost:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.6);
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        /* Main Content */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            min-height: calc(100vh - 100px);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content {
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 100px;
            font-size: 0.8rem;
            color: #60a5fa;
            margin-bottom: 1.5rem;
        }

        .hero-badge-dot {
            width: 6px;
            height: 6px;
            background: #3b82f6;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.03em;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.15rem;
            color: #a1a1aa;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .features {
            list-style: none;
            margin-bottom: 2.5rem;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: #d4d4d8;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .features li:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(8px);
            color: #fff;
        }

        .features li svg {
            width: 20px;
            height: 20px;
            color: #3b82f6;
            transition: transform 0.3s ease;
        }

        .features li:hover svg {
            transform: scale(1.2);
            color: #60a5fa;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1rem;
            border-radius: 10px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary:active {
            transform: translateY(-1px) scale(0.98);
        }

        /* Hero Visual */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .visual-card {
            position: relative;
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1;
            cursor: pointer;
            transition: transform 0.4s ease;
        }

        .visual-card:hover {
            transform: translateY(-10px) rotateX(5deg);
        }

        .visual-card:hover .visual-glow {
            opacity: 1;
            transform: scale(1.2);
        }

        .visual-card:active {
            transform: translateY(-5px) scale(0.98);
        }

        .visual-glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.3), transparent 70%);
            animation: glowPulse 4s ease-in-out infinite;
            transition: all 0.4s ease;
            opacity: 0.7;
        }

        .visual-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #1e1e2e, #2a2a3e);
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 100px rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
        }

        .visual-card:hover .visual-icon {
            box-shadow:
                0 30px 60px -12px rgba(0, 0, 0, 0.6),
                0 0 150px rgba(59, 130, 246, 0.4);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translate(-50%, -50%) scale(1.05);
        }

        .visual-icon img {
            width: 140px;
            height: 140px;
            object-fit: contain;
            margin-bottom: 0.5rem;
            transition: transform 0.4s ease;
        }

        .visual-card:hover .visual-icon img {
            transform: scale(1.1) rotate(5deg);
        }

        .visual-text {
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Floating elements */
        .floating {
            position: absolute;
            animation: float 6s ease-in-out infinite;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .floating:hover {
            transform: scale(1.2) !important;
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.3);
            z-index: 10;
        }

        .floating:active {
            transform: scale(0.95) !important;
        }

        .floating-1 {
            top: 10%;
            right: -20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .floating-2 {
            bottom: 20%;
            left: -30px;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(59, 130, 246, 0.2));
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation-delay: -2s;
        }

        .floating-3 {
            top: 40%;
            right: -40px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(236, 72, 153, 0.2));
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation-delay: -4s;
        }

        /* Stats Section */
        .stats {
            display: flex;
            gap: 3rem;
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            transition: color 0.3s ease;
        }

        .stat-item:hover .stat-value {
            color: #60a5fa;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #71717a;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Staggered animations for lists */
        .stagger-1 {
            transition-delay: 0.1s;
        }

        .stagger-2 {
            transition-delay: 0.2s;
        }

        .stagger-3 {
            transition-delay: 0.3s;
        }

        .stagger-4 {
            transition-delay: 0.4s;
        }

        .stagger-5 {
            transition-delay: 0.5s;
        }

        /* Interactive cursor */
        .interactive {
            cursor: pointer;
        }

        /* Button ripple effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 50%;
            left: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            transform: scale(0);
            transition: transform 0.5s ease, opacity 0.5s ease;
            opacity: 0;
            pointer-events: none;
        }

        .ripple:active::after {
            transform: scale(2);
            opacity: 1;
            transition: transform 0s, opacity 0s;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .features {
                display: inline-block;
                text-align: left;
            }

            .cta-buttons {
                justify-content: center;
            }

            .visual-card {
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .header {
                padding: 1rem 1.5rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .stats {
                gap: 2rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .visual-card {
                max-width: 300px;
            }

            .visual-icon {
                width: 160px;
                height: 160px;
            }

            .visual-icon img {
                width: 100px;
                height: 100px;
            }

            .visual-text {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>

    <!-- Header -->
    <header class="header">
        <a href="/" class="logo interactive">
            <img src="/images/shark-gpt-icon.png" alt="Shark GPT">
            <span class="logo-text">SHARK GPT</span>
        </a>

        <div class="nav-buttons">
            @auth
            <a href="/dashboard" class="btn btn-primary ripple">Dashboard</a>
            @else
            <a href="/login" class="btn btn-ghost ripple">Log in</a>
            <a href="/register" class="btn btn-primary ripple">Get Started</a>
            <!-- Google Sign In Button -->
            <a href="{{ route('auth.google') }}" class="btn btn-google ripple" title="Sign in with Google">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
            </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="hero">
            <!-- Left Content -->
            <div class="hero-content reveal-left">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    New Generation AI Chat
                </div>

                <h1>Experience the Future of <span>AI Conversation</span></h1>

                <p class="hero-description">
                    SHARK GPT delivers powerful AI chat capabilities with intelligent responses,
                    seamless conversation history, and enterprise-grade security.
                    Start chatting with cutting-edge AI today.
                </p>

                <ul class="features">
                    <li class="interactive reveal stagger-1">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Intelligent AI-powered conversations
                    </li>
                    <li class="interactive reveal stagger-2">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Secure authentication & data protection
                    </li>
                    <li class="interactive reveal stagger-3">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Organized conversation history & library
                    </li>
                    <li class="interactive reveal stagger-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Multi-language support
                    </li>
                </ul>

                <div class="cta-buttons reveal stagger-5">
                    @auth
                    <a href="/dashboard" class="btn btn-primary btn-large ripple">Go to Dashboard</a>
                    @else
                    <a href="/register" class="btn btn-primary btn-large ripple">Start Chatting Free</a>
                    <a href="/login" class="btn btn-secondary btn-large ripple">Sign In</a>
                    @endauth
                </div>


            </div>

            <!-- Right Visual -->
            <div class="hero-visual">
                <div class="visual-card interactive">
                    <div class="floating floating-1"></div>
                    <div class="floating floating-2"></div>
                    <div class="floating floating-3"></div>
                    <div class="visual-glow"></div>
                    <div class="visual-icon">
                        <img src="/images/shark-gpt-icon.png" alt="Shark GPT">
                        <div class="visual-text">SHARK<br>GPT</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
    // Scroll Reveal Animation using Intersection Observer
    document.addEventListener('DOMContentLoaded', function() {
        // Create intersection observer
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Optional: Stop observing once revealed
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all reveal elements
        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
            observer.observe(el);
        });

        // Parallax effect for floating elements on mouse move
        const visualCard = document.querySelector('.visual-card');
        if (visualCard) {
            visualCard.addEventListener('mousemove', (e) => {
                const rect = visualCard.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                visualCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
            });

            visualCard.addEventListener('mouseleave', () => {
                visualCard.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover sound effect (optional - disabled by default)
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transitionTimingFunction = 'cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transitionTimingFunction = 'cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });
    });

    // Add entrance animation when page loads
    window.addEventListener('load', function() {
        // Stagger the hero content animations
        const heroContent = document.querySelector('.hero-content');
        if (heroContent) {
            heroContent.style.animationPlayState = 'running';
        }
    });
</script>

</html>
