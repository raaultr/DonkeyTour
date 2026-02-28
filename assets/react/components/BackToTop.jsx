import React, { useState, useEffect } from 'react';

export default function BackToTop() {
    // Estado para mostrar (true) u ocultar (false) el botón
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        // Si el scroll baja de 400px, activa el botón
        const onScroll = () => setVisible(window.scrollY > 400);
        
        // Escucha el evento de scroll en el navegador
        window.addEventListener('scroll', onScroll, { passive: true });
        
        // Limpieza: elimina el evento al cambiar de página (evita errores con Turbo)
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    // Sube al inicio de la página con desplazamiento suave
    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <button
            onClick={scrollToTop}
            aria-label="Volver arriba"
            /* Diseño: Fijo abajo a la derecha, color marrón y sombra suave */
            className={`fixed bottom-6 right-6 z-50 w-12 h-12 bg-[#8B5E3C] hover:bg-[#764f32] text-white rounded-2xl shadow-lg shadow-[#8B5E3C]/30 flex items-center justify-center transition-all duration-300 ${
                /* Animación: Si es visible aparece, si no, baja y se vuelve invisible */
                visible
                    ? 'opacity-100 translate-y-0'
                    : 'opacity-0 translate-y-4 pointer-events-none'
            }`}
        >
            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5">
                <path strokeLinecap="round" strokeLinejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    );
}
