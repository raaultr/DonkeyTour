import React, { useState, useEffect, useRef } from 'react';

export default function AnimatedCounter({ target, label, icon, suffix = '' }) {
    const [count, setCount] = useState(0);
    const [hasAnimated, setHasAnimated] = useState(false);
    const ref = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting && !hasAnimated) {
                    setHasAnimated(true);
                    animateCount(0, target, 1500);
                }
            },
            { threshold: 0.3 }
        );

        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [target, hasAnimated]);

    const animateCount = (start, end, duration) => {
        const startTime = performance.now();
        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Easing: easeOutExpo
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            setCount(Math.floor(start + (end - start) * eased));
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    };

    return (
        <div
            ref={ref}
            className="bg-white/90 backdrop-blur-xl border border-stone-100 rounded-[2rem] p-8 shadow-[0_15px_40px_rgba(0,0,0,0.06)] text-center group hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-300"
        >
            <div className="w-14 h-14 bg-[#8B5E3C]/10 rounded-2xl flex items-center justify-center text-2xl mb-4 mx-auto group-hover:scale-110 transition-transform">
                {icon}
            </div>
            <p className="text-4xl font-bold text-[#3E2F28] tabular-nums">
                {count > 0 ? '+' : ''}{count}{suffix}
            </p>
            <p className="text-stone-400 text-sm font-medium mt-1 uppercase tracking-wider">{label}</p>
        </div>
    );
}

/**
 * Wrapper: renderiza un grupo de contadores
 * Recibe los datos desde data-counters='[...]' en el HTML
 */
export function CounterGroup() {
    const [counters, setCounters] = useState([]);

    useEffect(() => {
        const el = document.getElementById('react-counters');
        if (el?.dataset.counters) {
            try {
                setCounters(JSON.parse(el.dataset.counters));
            } catch (e) {
                console.error('Error parsing counters data:', e);
            }
        }
    }, []);

    if (counters.length === 0) return null;

    return (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {counters.map((c, i) => (
                <AnimatedCounter
                    key={i}
                    target={c.target}
                    label={c.label}
                    icon={c.icon}
                    suffix={c.suffix || ''}
                />
            ))}
        </div>
    );
}
