import React, { useState, useEffect, useRef } from 'react';

export default function DonkeyCarousel() {
    const [donkeys, setDonkeys] = useState([]);
    const [loading, setLoading] = useState(true);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [isAutoPlaying, setIsAutoPlaying] = useState(true);
    const containerRef = useRef(null);

    useEffect(() => {
        fetch('/api/donkeys')
            .then(res => res.json())
            .then(data => {
                setDonkeys(data);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, []);

    // Autoplay
    useEffect(() => {
        if (!isAutoPlaying || donkeys.length === 0) return;
        const timer = setInterval(() => {
            setCurrentIndex(prev => (prev + 1) % donkeys.length);
        }, 4000);
        return () => clearInterval(timer);
    }, [isAutoPlaying, donkeys.length]);

    const goTo = (i) => {
        setCurrentIndex(i);
        setIsAutoPlaying(false);
        setTimeout(() => setIsAutoPlaying(true), 8000);
    };

    const prev = () => goTo((currentIndex - 1 + donkeys.length) % donkeys.length);
    const next = () => goTo((currentIndex + 1) % donkeys.length);

    if (loading) {
        return (
            <div className="flex items-center justify-center py-16">
                <div className="w-10 h-10 border-4 border-[#8B5E3C] border-t-transparent rounded-full animate-spin"></div>
            </div>
        );
    }

    if (donkeys.length === 0) return null;

    // Calcular los 3 burros visibles (anterior, actual, siguiente)
    const getVisibleDonkeys = () => {
        const result = [];
        for (let offset = -1; offset <= 1; offset++) {
            const idx = (currentIndex + offset + donkeys.length) % donkeys.length;
            result.push({ ...donkeys[idx], offset });
        }
        return result;
    };

    const visible = getVisibleDonkeys();

    return (
        <div className="relative">
            {/* Header */}
            <div className="text-center mb-10">
                <span className="text-[#8B5E3C] font-bold tracking-widest uppercase text-xs">Conócelos</span>
                <h2 className="text-3xl md:text-4xl font-bold text-[#3E2F28] mt-3">Nuestros Compañeros</h2>
                <p className="text-stone-400 mt-3 max-w-lg mx-auto text-sm">
                    Cada uno con su personalidad única. Desliza para conocerlos a todos.
                </p>
            </div>

            {/* Carousel */}
            <div className="relative overflow-hidden" ref={containerRef}>
                <div className="flex items-center justify-center gap-6 px-4 py-4">
                    {visible.map((donkey, i) => {
                        const isCenter = donkey.offset === 0;
                        return (
                            <div
                                key={`${donkey.id}-${donkey.offset}`}
                                className={`transition-all duration-500 ease-out flex-shrink-0 ${
                                    isCenter
                                        ? 'w-72 md:w-80 scale-100 opacity-100 z-10'
                                        : 'w-56 md:w-64 scale-90 opacity-50 hidden md:block'
                                }`}
                            >
                                <DonkeyCard donkey={donkey} isCenter={isCenter} />
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Controles */}
            <div className="flex items-center justify-center gap-4 mt-8">
                <button
                    onClick={prev}
                    className="w-10 h-10 rounded-full bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:text-[#8B5E3C] hover:border-[#8B5E3C] transition-all shadow-sm"
                >
                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>

                {/* Dots */}
                <div className="flex gap-2">
                    {donkeys.map((_, i) => (
                        <button
                            key={i}
                            onClick={() => goTo(i)}
                            className={`transition-all duration-300 rounded-full ${
                                i === currentIndex
                                    ? 'w-8 h-3 bg-[#8B5E3C]'
                                    : 'w-3 h-3 bg-stone-200 hover:bg-stone-300'
                            }`}
                        />
                    ))}
                </div>

                <button
                    onClick={next}
                    className="w-10 h-10 rounded-full bg-white border border-stone-200 flex items-center justify-center text-stone-500 hover:text-[#8B5E3C] hover:border-[#8B5E3C] transition-all shadow-sm"
                >
                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {/* Ver todos link */}
            <div className="text-center mt-6">
                <a href="/donkey" className="inline-flex items-center gap-2 text-[#4A90A4] font-bold text-sm hover:underline">
                    Ver todos los burros
                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    );
}

function DonkeyCard({ donkey, isCenter }) {
    const photoUrl = donkey.photoUrl || '/img/burro1.jpg';

    return (
        <div className={`bg-white rounded-3xl overflow-hidden border border-stone-100 transition-all duration-500 ${
            isCenter ? 'shadow-xl' : 'shadow-sm'
        }`}>
            {/* Foto */}
            <div className="relative h-52 overflow-hidden">
                <img
                    src={photoUrl}
                    alt={donkey.nombre}
                    className="w-full h-full object-cover"
                />
                {donkey.disponible && (
                    <span className="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                        Disponible
                    </span>
                )}
            </div>

            {/* Info */}
            <div className="p-5">
                <div className="flex items-center gap-2 mb-2">
                    <span className="text-lg">🐴</span>
                    <h3 className="text-lg font-bold text-[#3E2F28]">{donkey.nombre}</h3>
                </div>

                <div className="grid grid-cols-2 gap-2 text-xs text-stone-400">
                    {donkey.years && (
                        <div className="flex items-center gap-1">
                            <span>🎂</span> {donkey.years} años
                        </div>
                    )}
                    {donkey.race && (
                        <div className="flex items-center gap-1">
                            <span>🏷️</span> {donkey.race}
                        </div>
                    )}
                    {donkey.kilogram && (
                        <div className="flex items-center gap-1">
                            <span>⚖️</span> {donkey.kilogram} kg
                        </div>
                    )}
                </div>

                {isCenter && (
                    <a
                        href={`/donkey/${donkey.id}`}
                        className="block mt-4 text-center py-2.5 border-2 border-[#8B5E3C] text-[#8B5E3C] font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-[#8B5E3C] hover:text-white transition-all"
                    >
                        Conocer a {donkey.nombre}
                    </a>
                )}
            </div>
        </div>
    );
}
