import React, { useState, useEffect, useRef } from 'react';

/**
 * COMPONENTE: AnimatedCounter
 * Crea un número que sube de 0 hasta un objetivo con animación suave.
 */
export default function AnimatedCounter({ target, label, icon, suffix = '' }) {
    const [count, setCount] = useState(0); // Valor actual del contador
    const [hasAnimated, setHasAnimated] = useState(false); // Evita repetir la animación
    const ref = useRef(null); // Referencia al elemento para el Observer

    useEffect(() => {
        // IntersectionObserver: Detecta cuando el contador aparece en pantalla
        const observer = new IntersectionObserver(
            ([entry]) => {
                // Si el usuario llega al elemento y no ha animado antes, dispara la función
                if (entry.isIntersecting && !hasAnimated) {
                    setHasAnimated(true);
                    animateCount(0, target, 1500); // 1.5 segundos de duración
                }
            },
            { threshold: 0.3 } // Se activa cuando el 30% del componente es visible
        );

        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect(); // Limpieza al cambiar de página
    }, [target, hasAnimated]);

    // Lógica matemática de la animación (Easing)
    const animateCount = (start, end, duration) => {
        const startTime = performance.now();
        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // "EaseOutExpo": La animación empieza rápido y frena al final
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            
            setCount(Math.floor(start + (end - start) * eased));
            
            if (progress < 1) {
                requestAnimationFrame(step); // Siguiente frame de la animación
            }
        };
        requestAnimationFrame(step);
    };

    return (
        <div ref={ref} className="text-center group transition-all duration-300">
            <div className="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-2xl mb-4 mx-auto group-hover:scale-110 transition-transform">
                {icon}
            </div>
            <p className="text-4xl md:text-5xl font-bold text-white tabular-nums">
                {count > 0 ? '+' : ''}{count}{suffix}
            </p>
            <p className="text-white/60 text-xs font-bold mt-2 uppercase tracking-widest">{label}</p>
        </div>
    );
}

/**
 * WRAPPER: CounterGroup
 * Carga los datos desde un atributo HTML (JSON) y genera los contadores.
 */
export function CounterGroup() {
    const [counters, setCounters] = useState([]);

    useEffect(() => {
        // Leemos los datos directamente del div con id 'react-counters' en Twig
        const el = document.getElementById('react-counters');
        if (el?.dataset.counters) {
            try {
                setCounters(JSON.parse(el.dataset.counters));
            } catch (e) {
                console.error('Error al parsear datos de contadores:', e);
            }
        }
    }, []);

    if (counters.length === 0) return null;

    return (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
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