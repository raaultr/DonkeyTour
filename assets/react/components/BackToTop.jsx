import React, { useState, useEffect } from 'react';

export default function BackToTop() {
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        const onScroll = () => setVisible(window.scrollY > 400);
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <button
            onClick={scrollToTop}
            aria-label="Volver arriba"
            className={`fixed bottom-6 right-6 z-50 w-12 h-12 bg-[#8B5E3C] hover:bg-[#764f32] text-white rounded-2xl shadow-lg shadow-[#8B5E3C]/30 flex items-center justify-center transition-all duration-300 ${
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
